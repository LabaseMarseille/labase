<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/{_locale}/'
)]
class HomeController extends AbstractController
{
    #[Route(
        path: '',
        name: 'app_index',
        requirements: ['_locale' => 'fr|en'],
    )]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
