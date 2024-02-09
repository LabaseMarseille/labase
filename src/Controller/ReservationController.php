<?php

namespace App\Controller;

use App\Entity\Referents;
use App\Entity\Reservation;
use App\Entity\Collectif;
use App\Form\ReservationType;
use App\Repository\CollectifRepository;
use App\Repository\CouleurRepository;
use App\Repository\DemandeRepository;
use App\Repository\EtapeRepository;
use App\Repository\MailreservationRepository;
use App\Repository\ReferentsRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/index/{filtre}', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, EtapeRepository $etapeRepository, $filtre="aucuns"): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_REFPROG');

        $etapes=$etapeRepository->findBy(array('cloture'=>false), array('ordre'=>'ASC'));

        return $this->render('reservation/index.html.twig', [
            //'reservations' => $reservationRepository->findAll(),
            'etapes'=>$etapes, 'filtre'=>$filtre
        ]);
    }

    #[Route('/programme', name: 'app_reservation_programme', methods: ['GET'])]
    public function programme(ReservationRepository $reservationRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_REFPROG');

        $nomJours=['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
        $nommois=['janvier', 'février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
        //dump($nomJours[2]);
        setlocale(LC_TIME, "fr_FR");

        $journee=new \DateTime();
        $journee->setTime(0, 0);



        $programme=[];
        $semaine=[];
        for ($i=1; $i<=7; $i++){

            $sem= clone $journee;

            $semaine[]=[
                'jour'=>$nomJours[$i],
                'mois'=>$nommois[ $sem->format('m')-1],
                'datejour'=> clone $sem,
                'num'=>$i
                ];

            $journeeSuivante= clone $journee;
            $journeeSuivante=$journeeSuivante->modify('+1 day');
            $reservations=$reservationRepository->listeProgramme($journee,$journeeSuivante);
            if ($reservations){
                foreach ($reservations as $reservation){
                    $programme[]=[
                        'num'=>$i,
                        'titre'=>$reservation->getTitre(),
                        'datedebut'=>$reservation->getDatedebut()->format('H:i'),
                        'datefin'=>$reservation->getDatefin()->format('H:i'),
                        'salle'=>$reservation->getSalles(),
                        'type'=>$reservation->getTypereservation()->getLibelle(),
                        'collectif'=>$reservation->getCollectif()->getNom()
                    ];
                }
            }
            $journee=$journee->modify('+1 day');
        }

        return $this->render('reservation/programme.html.twig',[
            'programmes'=>$programme, 'semaines'=>$semaine,
        ]);
    }

    #[Route('/calendrier', name: 'app_reservation_celendrier', methods: ['GET'])]
    public function calendrier(ReservationRepository $reservationRepository,
                ReferentsRepository $referentsRepository, CouleurRepository $couleurRepository): Response
    {
        $events=$reservationRepository->findBy(array('calendrier'=>true));
        $rdvs=[];

        $couleurRec=$couleurRepository->findOneBy(array('libelle'=>'recurrent'));
        $couleurRes=$couleurRepository->findOneBy(array('libelle'=>'reservation'));
        foreach ($events as $event){

            if ($event->isRecurrent()){
                dump($couleurRec);
                $color=$couleurRec->getCouleur();
            }else{
                $color=$couleurRes->getCouleur();
            }
            $titre=null;
            if ($event->getSalles()){
                foreach ($event->getSalles() as $salle){
                    if ($titre){
                        $titre=$titre.'+'.$salle->getLibelle();
                    }else{
                        $titre=$salle->getLibelle();
                    }
                }
                $titre=$titre.' - '.$event->getTitre();
            }else{
                $titre=$event->getTitre();
            }

            $rdvs[]=[
                'id'=>$event->getId(),
                'type'=>'reservation',
                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$titre,
                'backgroundColor'=>$color,
                'description'=>$event->getDescription(),
                'allDay'=>false,
            ];
        }

        $referents=$referentsRepository->findBy(array('type'=>'refbase'));
        $couleur=$couleurRepository->findOneBy(array('libelle'=>'refbase'));
        foreach ($referents as $event){
            dump($event);
            $rdvs[]=[
                'id'=>$event->getId(),
                'type'=>'refbase',
                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>'Référent Base',
                'backgroundColor'=>$couleur->getCouleur(),
                'allDay'=>false,
            ];
        }
        //dump($rdvs);

        $referents=$referentsRepository->findBy(array('type'=>'refbar'));
        $couleur=$couleurRepository->findOneBy(array('libelle'=>'refbar'));
        foreach ($referents as $event){

            $rdvs[]=[
                'id'=>$event->getId(),
                'type'=>'refbar',
                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>'Référent Bar',
                'backgroundColor'=>$couleur->getCouleur(),
                'allDay'=>false,
            ];
        }
        $data=json_encode($rdvs);
        return $this->render('reservation/calendrier.html.twig',[
            'data'=>$data
        ]);
    }

    #[Route(path: '/multipriseenmain', name: 'multipriseenmain', methods: ['POST'])]
    public function multipriseenmain(RequestStack $requestStack, ReservationRepository $reservationRepository,
                                    UserRepository $userRepository, EntityManagerInterface $em)
    {
        $tableau=$requestStack->getCurrentRequest()->get('table');
        $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));
        foreach ($tableau as $table){
            $reservation=$reservationRepository->findOneBy(array('id'=>$table));
            $reservation->setPriseenmainby($user);
            $em->persist($reservation);
            $em->flush();

        }
        return new Response(1);
    }

    #[Route(path: '/changeetape', name: 'changeetape', methods: ['POST'])]
    public function changeetape(RequestStack $requestStack, ReservationRepository $reservationRepository,
                                     EtapeRepository $etapeRepository, EntityManagerInterface $em)
    {
        $tableau=$requestStack->getCurrentRequest()->get('table');
        $idEtape=$requestStack->getCurrentRequest()->get('etape');
        $etape=$etapeRepository->findOneBy(array('id'=>$idEtape));
        foreach ($tableau as $table){
            $reservation=$reservationRepository->findOneBy(array('id'=>$table));
            $reservation->setEtape($etape);
            $em->persist($reservation);
            $em->flush();

        }
        return new Response(1);
    }


    #[Route(path: '/miseaucalendrier', name: 'miseaucalendrier', methods: ['POST'])]
    public function miseaucalendrier(RequestStack $requestStack, ReservationRepository $reservationRepository,
                                      EntityManagerInterface $em)
    {
        $tableau=$requestStack->getCurrentRequest()->get('table');

        foreach ($tableau as $table){
            $reservation=$reservationRepository->findOneBy(array('id'=>$table));
            $reservation->setCalendrier(true);
            $em->persist($reservation);
            $em->flush();

        }
        return new Response(1);
    }
    #[Route(path: '/otercalendrier', name: 'otercalendrier', methods: ['POST'])]
    public function otercalendrier(RequestStack $requestStack, ReservationRepository $reservationRepository,
                                     EntityManagerInterface $em)
    {
        $tableau=$requestStack->getCurrentRequest()->get('table');

        foreach ($tableau as $table){
            $reservation=$reservationRepository->findOneBy(array('id'=>$table));
            $reservation->setCalendrier(false);
            $em->persist($reservation);
            $em->flush();
        }
        return new Response(1);
    }

    #[Route('/calendrier/editdate/{id}', name: 'app_reservation_majdate', methods: ['PUT','GET'])]
    public function majReservationDate(?Reservation $reservation, Request $request, EntityManagerInterface $em)
    {
        dump("ici");

        //recuperation des données de reservation
        $data=json_decode($request->getContent());
        /*  if (
              isset($data->title && !empty($data->title))
          )
          {

          }*/
        $code=201;
        $reservation->setTitre($data->title); //?
        $reservation->setDescription($data->description); //?
        $reservation->setDatedebut(new \DateTime($data->start));
        $reservation->setDatefin(new \DateTime($data->end));
        $em->persist($reservation);
        $em->flush();


        return new Response('OK, $code');
    }


    //renvoie les infos du poste passé en parametre (id)
    #[Route(path: '/ajxlookcollectif', name: 'app_reservation_ajxlookCollectif', methods: ['POST'])]
    public function ajxlookcollectif(RequestStack $requestStack, CollectifRepository $collectifRepository)
    {
        $id=$requestStack->getCurrentRequest()->get('id');
        $collectif = $collectifRepository->findOneBy(array('id'=>$id));
        //$refposte = $this->doctrine->getRepository(Refposte::class)->getOneRefPoste($id);
        $tab=[];
        $tab[] = ['nom'=>$collectif->getNom(),'abreviation'=>$collectif->getAbreviation(),
            'mail'=>$collectif->getMail(),'telephone'=>$collectif->getTelephone(),'siret'=>$collectif->getSiret(),
            'codepostal'=>$collectif->getCodepostal()
        ];
        return new JsonResponse($tab);
    }

    #[Route(path: '/ajxlookMail', name: 'app_reservation_ajxlookMail', methods: ['POST'])]
    public function ajxlookMail(RequestStack $requestStack,  CollectifRepository $collectifRepository)
    {
        $id=$requestStack->getCurrentRequest()->get('id');

        $collectif=$collectifRepository->findOneById($requestStack->getCurrentRequest()->get('collectif'));
        if ($id==1){
            if ($collectif){
                $mail=$collectif->getMail();
            }else{
                $mail="";
            }
        }elseif ($id==2){
            $mail=$this->getUser()->getUserIdentifier();
        }else{
            $mail="";
        }

        return new JsonResponse($mail);
    }

    #[route('/priseenmain/{id}', name: 'app_reservation_priseenmain', methods: ['GET'])]
    public function priseEnMain(Reservation $reservation, UserRepository $userRepository, EntityManagerInterface $em){

        $user=$userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        $reservation->setPriseenmainby($user);
        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reservationjson/{filtre}/{etape}', name: 'app_reservation_json', methods: ['GET'])]
    public function userJson(EntityManagerInterface $em, $filtre, $etape, UserRepository $userRepository): JsonResponse
    {
        $user=$userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
        $reservations = $em->getRepository(Reservation::class)->listeReservation($filtre,$user,$etape);
        $userJson=[];
        foreach ($reservations as $reservation) {
            $userJson[] = [
                'titre' => $reservation['titre'], 'id' => $reservation['id'],'collectif' => $reservation['collectif'],
                'datedebut' => $reservation['datedebut'] ? $reservation['datedebut']->format('d/m/Y H:i') : '-',
                'datefin' => $reservation['datefin'] ? $reservation['datefin']->format('d/m/Y H:i') : '-',
                'calendrier'=>$reservation['calendrier'] ? 'Oui' : 'Non',
                'priseenmain' => $reservation['priseenmain'],'reservepar' => $reservation['reservepar'],'etape' => $reservation['etape'],
                'datecreation' => $reservation['datecreation'] ? $reservation['datecreation']->format('d/m/Y') : '-'];
        }
        return new JsonResponse($userJson);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, EtapeRepository $etapeRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$userRepository->findOneByEmail($this->getUser()->getUserIdentifier());
            $reservation->setReserveby($user);
            $etape=$etapeRepository->findOneBy(array('ordre'=>1));
            $reservation->setEtape($etape);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/communication/{commfaite}/{commannulee}', name: 'app_reservation_communication', methods: ['GET'])]
    public function communication(ReservationRepository $reservationRepository, $commfaite=0, $commannulee=0): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_REFCOMM');

        $reservations=$reservationRepository->listeCommunication($commfaite,$commannulee );
        return $this->render('reservation/communication.html.twig', [
            'commfaite'=>$commfaite, 'commannulee'=>$commannulee, 'reservations'=>$reservations
        ]);
    }

    #[Route('/communicationFaite/{id}/{commfaite}/{commannulee}', name: 'app_reservation_commfaite', methods: ['GET'])]
    public function communicationFaite(ReservationRepository $reservationRepository, EntityManagerInterface $em, $id, $commfaite, $commannulee): Response
    {
        $reservation=$reservationRepository->findOneBy(array('id'=>$id));
        if ($reservation->isCommfaite()){
            $reservation->setCommfaite(false);
        }else{
            $reservation->setCommfaite(true);
        }
        $em->persist($reservation);
        $em->flush();
        return $this->redirectToRoute('app_reservation_communication', ['commfaite'=>$commfaite, 'commannulee'=>$commannulee]);
    }
    #[Route('/commannulee/{id}/{commfaite}/{commannulee}', name: 'app_reservation_commannulee', methods: ['GET'])]
    public function commannulee(ReservationRepository $reservationRepository, EntityManagerInterface $em, $id, $commfaite, $commannulee): Response
    {
        $reservation=$reservationRepository->findOneBy(array('id'=>$id));
        if ($reservation->isCommannulee()){
            $reservation->setCommannulee(false);
        }else{
            $reservation->setCommannulee(true);
        }
        $em->persist($reservation);
        $em->flush();
        return $this->redirectToRoute('app_reservation_communication', ['commfaite'=>$commfaite, 'commannulee'=>$commannulee]);
    }


    #[Route('/referentsbase', name: 'app_reservation_referentbase', methods: ['GET','POST'])]
    public function referentsbase(ReservationRepository $reservationRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $em,
                            ReferentsRepository $referentsRepository, CouleurRepository $couleurRepository): Response
    {

        //formulaire de creation event REF
        $dataForm=[];
        $formBuilder = $this->createFormBuilder($dataForm)
            ->add('datedebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('datefin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de fin',
                'attr' => ['class' => 'js-datepicker'],
            ]);
        $formRef = $formBuilder->getForm ();
        $formRef->handleRequest($request);

        if ($formRef->isSubmitted() && $formRef->isValid()) {

            $data = $formRef->getData();

            $referent=new Referents();
            $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));
            $referent->setUser($user);
            $referent->setTitre($user->getNom());
            $referent->setDatedebut($data['datedebut']);
            $referent->setDatefin($data['datefin']);
            $referent->setType('refbase');
            $em->persist($referent);
            $em->flush();
        }

        $events=$reservationRepository->findBy(array('calendrier'=>true));

        $rdvs=[];
        foreach ($events as $event){
            $rdvs[]=[
                'id'=>$event->getId(),
                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>$event->getDescription(),
                //backgroundcolor
                //bordercolor
                //textcolor
                'allDay'=>false,

            ];
        }

        $referents=$referentsRepository->findBy(array('type'=>'refbase'));
        $couleur=$couleurRepository->findOneBy(array('libelle'=>'refbase'));
        foreach ($referents as $event){
            $rdvs[]=[

                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>'Référent Base',
                'backgroundColor'=>$couleur->getCouleur(),
                'allDay'=>false,
            ];
        }
        $referents=$referentsRepository->findBy(array('type'=>'refbar'));
        $couleur=$couleurRepository->findOneBy(array('libelle'=>'refbar'));
        foreach ($referents as $event){
            $rdvs[]=[
                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>'Référent Bar',
                'backgroundColor'=>$couleur->getCouleur(),
                'allDay'=>false,
            ];
        }

        $data=json_encode($rdvs);
        return $this->render('reservation/calendrierref.html.twig',[
            'data'=>$data,
            'form'=>$formRef,
        ]);
    }

    #[Route('/referentsbar', name: 'app_reservation_referentbar', methods: ['GET','POST'])]
    public function referentsbar(ReservationRepository $reservationRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $em,
                              ReferentsRepository $referentsRepository, CouleurRepository $couleurRepository): Response
    {
        $couleurRec=$couleurRepository->findOneBy(array('libelle'=>'recurrent'));
        $couleurRes=$couleurRepository->findOneBy(array('libelle'=>'reservation'));
        //formulaire de creation event REF
        $dataForm=[];
        $formBuilder = $this->createFormBuilder($dataForm)
            ->add('datedebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('datefin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de fin',
                'attr' => ['class' => 'js-datepicker'],
            ]);
        $formRef = $formBuilder->getForm ();
        $formRef->handleRequest($request);

        if ($formRef->isSubmitted() && $formRef->isValid()) {

            $data = $formRef->getData();

            $referent=new Referents();
            $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));
            $referent->setUser($user);
            $referent->setTitre($user->getNom());
            $referent->setDatedebut($data['datedebut']);
            $referent->setDatefin($data['datefin']);
            $referent->setType('refbar');
            $em->persist($referent);
            $em->flush();
        }




        $events=$reservationRepository->findBy(array('calendrier'=>true));

        $rdvs=[];
        foreach ($events as $event){


            $rdvs[]=[
                'id'=>$event->getId(),
                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>$event->getDescription(),

                //'bordercolor'=>$color,
                //textcolor
                'allDay'=>false,

            ];
        }

        $referents=$referentsRepository->findAll();
        foreach ($referents as $event){
            $rdvs[]=[

                'start'=>$event->getDatedebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDatefin()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitre(),
                'description'=>'base',
                'backgroundcolor'=>'33ff74',
                //bordercolor
                //textcolor
                'allDay'=>false,

            ];
        }




        $data=json_encode($rdvs);
        return $this->render('reservation/calendrierref.html.twig',[
            'data'=>$data,
            'form'=>$formRef,
        ]);


    }


    #[Route('/detail/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit/{by}', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager, $by='user'): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation,['by' => $by] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/comm/detail', name: 'app_reservation_com_show', methods: ['GET', 'POST'])]
    public function commshow(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {

        dump($reservation);

        return $this->render('reservation/showcomm.html.twig', [
            'reservation' => $reservation,
        ]);

    }


    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
