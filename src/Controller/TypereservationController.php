<?php

namespace App\Controller;

use App\Entity\Typereservation;
use App\Form\TypereservationType;
use App\Repository\TypereservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typereservation')]
class TypereservationController extends AbstractController
{
    #[Route('/', name: 'app_typereservation_index', methods: ['GET'])]
    public function index(TypereservationRepository $typereservationRepository): Response
    {
        return $this->render('typereservation/index.html.twig', [
            'typereservations' => $typereservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typereservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typereservation = new Typereservation();
        $form = $this->createForm(TypereservationType::class, $typereservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typereservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_typereservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typereservation/new.html.twig', [
            'typereservation' => $typereservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typereservation_show', methods: ['GET'])]
    public function show(Typereservation $typereservation): Response
    {
        return $this->render('typereservation/show.html.twig', [
            'typereservation' => $typereservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typereservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typereservation $typereservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypereservationType::class, $typereservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_typereservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typereservation/edit.html.twig', [
            'typereservation' => $typereservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typereservation_delete', methods: ['POST'])]
    public function delete(Request $request, Typereservation $typereservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typereservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typereservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_typereservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
