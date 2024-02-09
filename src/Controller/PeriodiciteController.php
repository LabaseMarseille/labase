<?php

namespace App\Controller;

use App\Entity\Periodicite;
use App\Form\PeriodiciteType;
use App\Repository\PeriodiciteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/periodicite')]
class PeriodiciteController extends AbstractController
{
    #[Route('/', name: 'app_periodicite_index', methods: ['GET'])]
    public function index(PeriodiciteRepository $periodiciteRepository): Response
    {
        return $this->render('periodicite/index.html.twig', [
            'periodicites' => $periodiciteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_periodicite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $periodicite = new Periodicite();
        $form = $this->createForm(PeriodiciteType::class, $periodicite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($periodicite);
            $entityManager->flush();

            return $this->redirectToRoute('app_periodicite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('periodicite/new.html.twig', [
            'periodicite' => $periodicite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_periodicite_show', methods: ['GET'])]
    public function show(Periodicite $periodicite): Response
    {
        return $this->render('periodicite/show.html.twig', [
            'periodicite' => $periodicite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_periodicite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Periodicite $periodicite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeriodiciteType::class, $periodicite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_periodicite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('periodicite/edit.html.twig', [
            'periodicite' => $periodicite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_periodicite_delete', methods: ['POST'])]
    public function delete(Request $request, Periodicite $periodicite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$periodicite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($periodicite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_periodicite_index', [], Response::HTTP_SEE_OTHER);
    }
}
