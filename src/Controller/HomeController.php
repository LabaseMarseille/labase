<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(
        path: '/',
        name: 'app_index',
//        Pour la translation =>
//        path: '/{_locale}',
//        requirements: ['_locale' => 'fr|en'],
    )]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
