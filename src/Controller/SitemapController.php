<?php

namespace App\Controller;

use App\Service\SitemapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap_xml', methods: ['GET'])]
    public function index(SitemapGenerator $generator): Response
    {
        $file = $this->getParameter('kernel.project_dir') . '/public/sitemap.xml';
        if (!file_exists($file)) {
            // generate on demand
            $generator->generate();
        }

        if (!file_exists($file)) {
            return new Response('Sitemap not generated', 404);
        }

        $content = file_get_contents($file);
        $response = new Response($content, 200, ['Content-Type' => 'application/xml']);
        // cache 12h
        $response->setMaxAge(43200);
        $response->setSharedMaxAge(43200);
        return $response;
    }
}
