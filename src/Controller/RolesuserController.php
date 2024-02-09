<?php

namespace App\Controller;

use App\Entity\Rolesuser;
use App\Form\RolesuserType;
use App\Repository\RolesuserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rolesuser')]
class RolesuserController extends AbstractController
{
    #[Route('/', name: 'app_rolesuser_index', methods: ['GET'])]
    public function index(RolesuserRepository $rolesuserRepository): Response
    {
        return $this->render('rolesuser/index.html.twig', [
            'rolesusers' => $rolesuserRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rolesuser_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rolesuser = new Rolesuser();
        $form = $this->createForm(RolesuserType::class, $rolesuser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rolesuser);
            $entityManager->flush();

            return $this->redirectToRoute('app_rolesuser_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rolesuser/new.html.twig', [
            'rolesuser' => $rolesuser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rolesuser_show', methods: ['GET'])]
    public function show(Rolesuser $rolesuser): Response
    {
        return $this->render('rolesuser/show.html.twig', [
            'rolesuser' => $rolesuser,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rolesuser_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rolesuser $rolesuser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RolesuserType::class, $rolesuser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rolesuser_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rolesuser/edit.html.twig', [
            'rolesuser' => $rolesuser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rolesuser_delete', methods: ['POST'])]
    public function delete(Request $request, Rolesuser $rolesuser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rolesuser->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rolesuser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rolesuser_index', [], Response::HTTP_SEE_OTHER);
    }
}
