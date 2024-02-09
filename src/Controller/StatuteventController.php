<?php

namespace App\Controller;

use App\Entity\Statutevent;
use App\Form\StatuteventType;
use App\Repository\StatuteventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statutevent')]
class StatuteventController extends AbstractController
{
    #[Route('/', name: 'app_statutevent_index', methods: ['GET'])]
    public function index(StatuteventRepository $statuteventRepository): Response
    {
        return $this->render('statutevent/index.html.twig', [
            'statutevents' => $statuteventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_statutevent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statutevent = new Statutevent();
        $form = $this->createForm(StatuteventType::class, $statutevent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statutevent);
            $entityManager->flush();

            return $this->redirectToRoute('app_statutevent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statutevent/new.html.twig', [
            'statutevent' => $statutevent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statutevent_show', methods: ['GET'])]
    public function show(Statutevent $statutevent): Response
    {
        return $this->render('statutevent/show.html.twig', [
            'statutevent' => $statutevent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statutevent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statutevent $statutevent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatuteventType::class, $statutevent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_statutevent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statutevent/edit.html.twig', [
            'statutevent' => $statutevent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statutevent_delete', methods: ['POST'])]
    public function delete(Request $request, Statutevent $statutevent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statutevent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($statutevent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_statutevent_index', [], Response::HTTP_SEE_OTHER);
    }
}
