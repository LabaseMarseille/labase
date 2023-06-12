<?php

namespace App\Skeleton\Controller;

use App\Skeleton\Service\Siamu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiamuController extends AbstractController
{
    #[Route('/maintenance', name: 'app_siamu_maintenance')]
    public function index(Siamu $siamu): Response
    {
        if (!$siamu->isMaintenanceModeRequired()) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('siamu/index.html.twig', [
            'controller_name' => 'SiamuController',
        ]);
    }
}
