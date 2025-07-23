<?php

namespace App\Controller;

use App\Service\SitemapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', methods: ['GET'])]
    public function sitemap(SitemapService $sitemapService): Response
    {
        $sitemapContent = $sitemapService->generateSitemap();

        $response = new Response($sitemapContent);
        $response->headers->set('Content-Type', 'application/xml');
        
        // Cache pour 24 heures
        $response->setMaxAge(86400);
        $response->setSharedMaxAge(86400);
        
        return $response;
    }
}
