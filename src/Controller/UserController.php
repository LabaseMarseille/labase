<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/userjson', name: 'app_user_json', methods: ['GET'])]
    public function userJson(EntityManagerInterface $em): JsonResponse
    {

        $users = $em->getRepository(User::class)->listeUser();

        $userJson = [];
        foreach ($users as $user) {
            $userJson[] = [
                'nom' => $user['nom'], 'telephone' => $user['telephone'],
                'email' => $user['email'], 'id' => $user['id'],
                'datecreation' => $user['datecreation'] ? $user['datecreation']->format('d/m/Y') : '-'];

                    //$ref->getDatDebValS() ? $ref->getDatDebValS()->format('d/m/Y') : '-',];

        }
        return new JsonResponse($userJson);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/monespace', name: 'app_user_show', methods: ['GET'])]
    public function show(UserRepository $userRepository, ReservationRepository $reservationRepository): Response
    {
        $user=$userRepository->findOneByEmail($this->getUser()->getUserIdentifier());


        //$reservations=$reservationRepository->findBy(array('reserveby'=>$user));
        $reservations=$reservationRepository->listeReservationByUser($this->getUser()->getUserIdentifier(), 'encours');
        $reservationspassees=$reservationRepository->listeReservationByUser($this->getUser()->getUserIdentifier(), 'passees');
        //$reservations=$reservationRepository->findBy(array('reserveby'=>$user));

        dump($reservationspassees);

        return $this->render('user/show.html.twig', [
            'user' => $user, 'reservations'=>$reservations, 'reservationspassees'=>$reservationspassees
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        dump($this->getUser());
        $roles=$this->getUser()->getRoles();
dump($roles);
        $form = $this->createForm(UserType::class, $user,['roles' => $roles]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $rolesusers=$form->getData()->getRolesusers();
            $tab=[];
            foreach ($rolesusers as $role){

                $tab[]=$role->getLibelle();
            }

            $user->setRoles($tab);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
