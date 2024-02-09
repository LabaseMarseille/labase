<?php

namespace App\Controller;

use App\Entity\Besoinbar;
use App\Form\BesoinbarType;
use App\Repository\BesoinbarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/besoinbar')]
class BesoinbarController extends AbstractController
{
    #[Route('/', name: 'app_besoinbar_index', methods: ['GET'])]
    public function index(BesoinbarRepository $besoinbarRepository): Response
    {
        return $this->render('besoinbar/index.html.twig', [
            'besoinbars' => $besoinbarRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_besoinbar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $besoinbar = new Besoinbar();
        $form = $this->createForm(BesoinbarType::class, $besoinbar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($besoinbar);
            $entityManager->flush();

            return $this->redirectToRoute('app_besoinbar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('besoinbar/new.html.twig', [
            'besoinbar' => $besoinbar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_besoinbar_show', methods: ['GET'])]
    public function show(Besoinbar $besoinbar): Response
    {
        return $this->render('besoinbar/show.html.twig', [
            'besoinbar' => $besoinbar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_besoinbar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Besoinbar $besoinbar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BesoinbarType::class, $besoinbar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_besoinbar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('besoinbar/edit.html.twig', [
            'besoinbar' => $besoinbar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_besoinbar_delete', methods: ['POST'])]
    public function delete(Request $request, Besoinbar $besoinbar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$besoinbar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($besoinbar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_besoinbar_index', [], Response::HTTP_SEE_OTHER);
    }
}
