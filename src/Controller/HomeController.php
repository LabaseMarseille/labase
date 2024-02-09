<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    #[Route(
        path: '/',
        name: 'app_index',
    )]
    public function index(): Response
    {
        // Si on ne fait pas ça, avec la translation, on arrive pas sur homepage.
        return $this->redirectToRoute('app_homepage');
    }

    #[Route(
        path: '/{_locale}/',
        name: 'app_homepage',
        requirements: ['_locale' => 'fr|en'],
    )]
    public function homepage(ReservationRepository $reservationRepository): Response
    {
        return $this->render('home/index.html.twig');
    }






}
