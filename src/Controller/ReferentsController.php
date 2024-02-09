<?php

namespace App\Controller;

use App\Entity\Referents;
use App\Form\ReferentsType;
use App\Repository\ReferentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/referents')]
class ReferentsController extends AbstractController
{
    #[Route('/', name: 'app_referents_index', methods: ['GET'])]
    public function index(ReferentsRepository $referentsRepository): Response
    {
        return $this->render('referents/index.html.twig', [
            'referents' => $referentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_referents_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $referent = new Referents();
        $form = $this->createForm(ReferentsType::class, $referent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($referent);
            $entityManager->flush();

            return $this->redirectToRoute('app_referents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('referents/new.html.twig', [
            'referent' => $referent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referents_show', methods: ['GET'])]
    public function show(Referents $referent): Response
    {
        return $this->render('referents/show.html.twig', [
            'referent' => $referent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_referents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Referents $referent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReferentsType::class, $referent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_referents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('referents/edit.html.twig', [
            'referent' => $referent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referents_delete', methods: ['POST'])]
    public function delete(Request $request, Referents $referent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$referent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($referent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_referents_index', [], Response::HTTP_SEE_OTHER);
    }
}
