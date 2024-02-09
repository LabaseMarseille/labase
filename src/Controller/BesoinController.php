<?php

namespace App\Controller;

use App\Entity\Besoin;
use App\Form\BesoinType;
use App\Repository\BesoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/besoin')]
class BesoinController extends AbstractController
{
    #[Route('/', name: 'app_besoin_index', methods: ['GET'])]
    public function index(BesoinRepository $besoinRepository): Response
    {
        return $this->render('besoin/index.html.twig', [
            'besoins' => $besoinRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_besoin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $besoin = new Besoin();
        $form = $this->createForm(BesoinType::class, $besoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($besoin);
            $entityManager->flush();

            return $this->redirectToRoute('app_besoin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('besoin/new.html.twig', [
            'besoin' => $besoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_besoin_show', methods: ['GET'])]
    public function show(Besoin $besoin): Response
    {
        return $this->render('besoin/show.html.twig', [
            'besoin' => $besoin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_besoin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Besoin $besoin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BesoinType::class, $besoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_besoin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('besoin/edit.html.twig', [
            'besoin' => $besoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_besoin_delete', methods: ['POST'])]
    public function delete(Request $request, Besoin $besoin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$besoin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($besoin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_besoin_index', [], Response::HTTP_SEE_OTHER);
    }
}
