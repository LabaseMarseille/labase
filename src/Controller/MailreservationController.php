<?php

namespace App\Controller;

use App\Entity\Mailreservation;
use App\Form\MailreservationType;
use App\Repository\MailreservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mailreservation')]
class MailreservationController extends AbstractController
{
    #[Route('/', name: 'app_mailreservation_index', methods: ['GET'])]
    public function index(MailreservationRepository $mailreservationRepository): Response
    {
        return $this->render('mailreservation/index.html.twig', [
            'mailreservations' => $mailreservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mailreservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mailreservation = new Mailreservation();
        $form = $this->createForm(MailreservationType::class, $mailreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mailreservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_mailreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mailreservation/new.html.twig', [
            'mailreservation' => $mailreservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mailreservation_show', methods: ['GET'])]
    public function show(Mailreservation $mailreservation): Response
    {
        return $this->render('mailreservation/show.html.twig', [
            'mailreservation' => $mailreservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mailreservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mailreservation $mailreservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MailreservationType::class, $mailreservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mailreservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mailreservation/edit.html.twig', [
            'mailreservation' => $mailreservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mailreservation_delete', methods: ['POST'])]
    public function delete(Request $request, Mailreservation $mailreservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mailreservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mailreservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mailreservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
