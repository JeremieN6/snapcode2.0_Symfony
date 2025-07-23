<?php

namespace App\Service;

use App\Repository\PostsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapService
{
    public function __construct(
        private PostsRepository $postsRepository,
        private CategoriesRepository $categoriesRepository,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function generateSitemap(): string
    {
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        // Créer l'élément racine
        $urlset = $xml->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        $xml->appendChild($urlset);

        // Pages statiques
        $staticPages = [
            ['route' => 'app_home', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['route' => 'blog_index', 'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        foreach ($staticPages as $page) {
            $url = $xml->createElement('url');
            
            $loc = $xml->createElement('loc');
            $loc->appendChild($xml->createTextNode(
                $this->urlGenerator->generate($page['route'], [], UrlGeneratorInterface::ABSOLUTE_URL)
            ));
            $url->appendChild($loc);
            
            $lastmod = $xml->createElement('lastmod');
            $lastmod->appendChild($xml->createTextNode(date('Y-m-d')));
            $url->appendChild($lastmod);
            
            $changefreq = $xml->createElement('changefreq');
            $changefreq->appendChild($xml->createTextNode($page['changefreq']));
            $url->appendChild($changefreq);
            
            $priority = $xml->createElement('priority');
            $priority->appendChild($xml->createTextNode($page['priority']));
            $url->appendChild($priority);
            
            $urlset->appendChild($url);
        }

        // Articles du blog
        $posts = $this->postsRepository->findPublishedPosts();
        foreach ($posts as $post) {
            $url = $xml->createElement('url');
            
            $loc = $xml->createElement('loc');
            $loc->appendChild($xml->createTextNode(
                $this->urlGenerator->generate('blog_index', ['slug' => $post->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL)
            ));
            $url->appendChild($loc);
            
            $lastmod = $xml->createElement('lastmod');
            $lastmod->appendChild($xml->createTextNode(
                $post->getUpdatedAt() ? $post->getUpdatedAt()->format('Y-m-d') : $post->getCreatedAt()->format('Y-m-d')
            ));
            $url->appendChild($lastmod);
            
            $changefreq = $xml->createElement('changefreq');
            $changefreq->appendChild($xml->createTextNode('weekly'));
            $url->appendChild($changefreq);
            
            $priority = $xml->createElement('priority');
            $priority->appendChild($xml->createTextNode($post->isIsFavorite() ? '0.8' : '0.7'));
            $url->appendChild($priority);
            
            $urlset->appendChild($url);
        }

        // Catégories du blog
        $categories = $this->categoriesRepository->findAll();
        foreach ($categories as $category) {
            $url = $xml->createElement('url');
            
            $loc = $xml->createElement('loc');
            $loc->appendChild($xml->createTextNode(
                $this->urlGenerator->generate('blog_category', ['slug' => $category->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL)
            ));
            $url->appendChild($loc);
            
            $lastmod = $xml->createElement('lastmod');
            $lastmod->appendChild($xml->createTextNode(date('Y-m-d')));
            $url->appendChild($lastmod);
            
            $changefreq = $xml->createElement('changefreq');
            $changefreq->appendChild($xml->createTextNode('weekly'));
            $url->appendChild($changefreq);
            
            $priority = $xml->createElement('priority');
            $priority->appendChild($xml->createTextNode('0.6'));
            $url->appendChild($priority);
            
            $urlset->appendChild($url);
        }

        return $xml->saveXML();
    }
}
