<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    #[Route('/integration-html', name: 'app_service_integration')]
    public function integrationHTML(): Response
    {
        return $this->render('services/integration.html.twig', [
            'controller_name' => 'ServicesController',
        ]);
    }

    #[Route('/developpement-personnalise', name: 'app_service_webDevPersonalized')]
    public function webDevPersonalized(): Response
    {
        return $this->render('services/webDevPersonalized.html.twig', [
            'controller_name' => 'ServicesController',
        ]);
    }
}
