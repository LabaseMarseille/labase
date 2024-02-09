<?php

namespace App\Controller;

use App\Entity\Typereservant;
use App\Form\TypereservantType;
use App\Repository\TypereservantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typereservant')]
class TypereservantController extends AbstractController
{
    #[Route('/', name: 'app_typereservant_index', methods: ['GET'])]
    public function index(TypereservantRepository $typereservantRepository): Response
    {
        return $this->render('typereservant/index.html.twig', [
            'typereservants' => $typereservantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typereservant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typereservant = new Typereservant();
        $form = $this->createForm(TypereservantType::class, $typereservant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typereservant);
            $entityManager->flush();

            return $this->redirectToRoute('app_typereservant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typereservant/new.html.twig', [
            'typereservant' => $typereservant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typereservant_show', methods: ['GET'])]
    public function show(Typereservant $typereservant): Response
    {
        return $this->render('typereservant/show.html.twig', [
            'typereservant' => $typereservant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typereservant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typereservant $typereservant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypereservantType::class, $typereservant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_typereservant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typereservant/edit.html.twig', [
            'typereservant' => $typereservant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typereservant_delete', methods: ['POST'])]
    public function delete(Request $request, Typereservant $typereservant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typereservant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typereservant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_typereservant_index', [], Response::HTTP_SEE_OTHER);
    }
}
