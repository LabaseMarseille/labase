<?php

namespace App\Controller;

use App\Entity\Parametrerecurrence;
use App\Form\ParametrerecurrenceType;
use App\Repository\ParametrerecurrenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parametrerecurrence')]
class ParametrerecurrenceController extends AbstractController
{
    #[Route('/', name: 'app_parametrerecurrence_index', methods: ['GET'])]
    public function index(ParametrerecurrenceRepository $parametrerecurrenceRepository): Response
    {
        return $this->render('parametrerecurrence/index.html.twig', [
            'parametrerecurrences' => $parametrerecurrenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_parametrerecurrence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $parametrerecurrence = new Parametrerecurrence();
        $form = $this->createForm(ParametrerecurrenceType::class, $parametrerecurrence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($parametrerecurrence);
            $entityManager->flush();

            return $this->redirectToRoute('app_parametrerecurrence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parametrerecurrence/new.html.twig', [
            'parametrerecurrence' => $parametrerecurrence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parametrerecurrence_show', methods: ['GET'])]
    public function show(Parametrerecurrence $parametrerecurrence): Response
    {
        return $this->render('parametrerecurrence/show.html.twig', [
            'parametrerecurrence' => $parametrerecurrence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parametrerecurrence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parametrerecurrence $parametrerecurrence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParametrerecurrenceType::class, $parametrerecurrence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_parametrerecurrence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parametrerecurrence/edit.html.twig', [
            'parametrerecurrence' => $parametrerecurrence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parametrerecurrence_delete', methods: ['POST'])]
    public function delete(Request $request, Parametrerecurrence $parametrerecurrence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parametrerecurrence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($parametrerecurrence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_parametrerecurrence_index', [], Response::HTTP_SEE_OTHER);
    }
}
