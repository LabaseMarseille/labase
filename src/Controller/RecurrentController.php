<?php

namespace App\Controller;

use App\Entity\Recurrent;
use App\Entity\Reservation;
use App\Form\RecurrentType;
use App\Repository\EtapeRepository;
use App\Repository\ParametrerecurrenceRepository;
use App\Repository\RecurrentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recurrent')]
class RecurrentController extends AbstractController
{
    #[Route('/', name: 'app_recurrent_index', methods: ['GET'])]
    public function index(RecurrentRepository $recurrentRepository): Response
    {
        return $this->render('recurrent/index.html.twig', [
            'recurrents' => $recurrentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recurrent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $recurrent = new Recurrent();
        $form = $this->createForm(RecurrentType::class, $recurrent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));
            $recurrent->setReserveby($user);
            $entityManager->persist($recurrent);
            $entityManager->flush();

            return $this->redirectToRoute('app_recurrent_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('recurrent/new.html.twig', [
            'recurrent' => $recurrent,
            'form' => $form,
        ]);
    }

    #[Route('/valider/{id}', name: 'app_recurrent_valider', methods: ['GET'])]
    public function valider(EntityManagerInterface $entityManager, Recurrent $recurrent, EtapeRepository $etapeRepository,
    UserRepository $userRepository, ParametrerecurrenceRepository $parametrerecurrenceRepository): Response
    {
        $etape=$etapeRepository->findOneBy(array('id'=>4));
        $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));

        $parametredebut=$parametrerecurrenceRepository->findOneBy(array('id'=>1));
        $parametrefin=$parametrerecurrenceRepository->findOneBy(array('id'=>2));


        dump($parametredebut->getValeur());
        dump($parametrefin->getValeur());

        if ($recurrent->getPeriodicite()->getId()==1){
            $madateDebut=clone $recurrent->getDatedebut();
            $madateFin=clone $recurrent->getDatefin();

            while($madateDebut<=$parametrefin->getValeur()){

                $reservation = new Reservation();
                $reservation->setCollectif($recurrent->getCollectif());
                $reservation->setCalendrier(true);
                $reservation->setEtape($etape);
                $reservation->setDatedebut($madateDebut);
                $reservation->setDatefin($madateFin);
                $reservation->setTitre($recurrent->getTitre());
                $reservation->setAutrebesoin($recurrent->getAutrebesoin());
                $reservation->setBesoinbar($recurrent->getBesoinbar());
                $reservation->setDescription($recurrent->getDescription());
                $reservation->setEmail($recurrent->getEmail());
                $reservation->setGratuit($recurrent->isGratuit());
                $reservation->setMailreservation($recurrent->getMailreservation());
                $reservation->setNbpersonne($recurrent->getNbpersonne());
                $reservation->setReserveby($recurrent->getReserveby());
                $reservation->setStatutevent($recurrent->getStatutevent());
                $reservation->setTypereservation($recurrent->getTypereservation());
                $reservation->setPriseenmainby($user);
                $reservation->setRecurrent(true);
                foreach ($recurrent->getBesoins() as $besoin){
                    $reservation->addBesoin($besoin);
                }
                foreach ($recurrent->getSalles() as $salle){
                    $reservation->addSalle($salle);
                }
                foreach ($recurrent->getObjectifs() as $objectifs){
                    $reservation->addObjectif($objectifs);
                }

                $entityManager->persist($reservation);
                $entityManager->flush();


                $madateDebut->add( new \DateInterval('P7D'));
                $madateFin->add( new \DateInterval('P7D'));
            }


            //dump($recurrent->getDatedebut());


            /*
            $reservation = new Reservation();
            $reservation->setCollectif($recurrent->getCollectif());
            $reservation->setCalendrier(true);
            $reservation->setEtape($etape);
            $reservation->setDatedebut($recurrent->getDatedebut());
            $reservation->setDatefin($recurrent->getDatefin());
            $reservation->setTitre($recurrent->getTitre());

            $reservation->setAutrebesoin($recurrent->getAutrebesoin());
            $reservation->setBesoinbar($recurrent->getBesoinbar());
            $reservation->setDescription($recurrent->getDescription());
            $reservation->setEmail($recurrent->getEmail());
            $reservation->setGratuit($recurrent->isGratuit());
            $reservation->setMailreservation($recurrent->getMailreservation());
            $reservation->setNbpersonne($recurrent->getNbpersonne());
            $reservation->setReserveby($recurrent->getReserveby());
            $reservation->setStatutevent($recurrent->getStatutevent());
            $reservation->setTypereservation($recurrent->getTypereservation());
            $reservation->setPriseenmainby($user);
            foreach ($recurrent->getBesoins() as $besoin){
                $reservation->addBesoin($besoin);
            }*/


        }






exit;
        return $this->redirectToRoute('app_recurrent_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/{id}', name: 'app_recurrent_show', methods: ['GET'])]
    public function show(Recurrent $recurrent): Response
    {
        return $this->render('recurrent/show.html.twig', [
            'recurrent' => $recurrent,
        ]);
    }

    #[Route('/{id}/edit/{by}', name: 'app_recurrent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recurrent $recurrent, EntityManagerInterface $entityManager, $by='user'): Response
    {
        dump($by);
        $form = $this->createForm(RecurrentType::class, $recurrent,['by' => $by] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recurrent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recurrent/edit.html.twig', [
            'recurrent' => $recurrent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recurrent_delete', methods: ['POST'])]
    public function delete(Request $request, Recurrent $recurrent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recurrent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recurrent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recurrent_index', [], Response::HTTP_SEE_OTHER);
    }
}
