<?php

namespace App\Service;

use App\Repository\PostsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SitemapGenerator
{
    private PostsRepository $postsRepository;
    private CategoriesRepository $categoriesRepository;
    private UrlGeneratorInterface $urlGenerator;
    private string $projectDir;
    private string $baseUrl;

    public function __construct(PostsRepository $postsRepository, CategoriesRepository $categoriesRepository, UrlGeneratorInterface $urlGenerator, #[Autowire('%kernel.project_dir%')] string $projectDir, #[Autowire('%env(APP_BASE_URL)%')] string $baseUrl)
    {
        $this->postsRepository = $postsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->urlGenerator = $urlGenerator;
        $this->projectDir = $projectDir;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function generate(): void
    {
        $posts = $this->postsRepository->findPublishedPosts();
        $categories = $this->categoriesRepository->findAll();
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        // Homepage
        $this->writeUrl($xml, $this->baseUrl, null, 'weekly', '1.0');

        // Static routes example (add more if needed)
        $this->writeUrl($xml, $this->baseUrl . '/about-me', null, 'weekly', '0.9');

        // Categories
        foreach ($categories as $category) {
            try {
                $path = $this->urlGenerator->generate('app_category', ['slug' => $category->getSlug()]);
            } catch (\Throwable $e) {
                continue;
            }
            $loc = $this->makeAbsoluteUrl($path);
            $this->writeUrl($xml, $loc, null, 'weekly', '0.8');
        }

        // Posts
        foreach ($posts as $post) {
            $path = $this->urlGenerator->generate('article_show', ['slug' => $post->getSlug()]);
            $loc = $this->makeAbsoluteUrl($path);
            $lastmod = null;
            if ($post->getUpdatedAt()) {
                $lastmod = $post->getUpdatedAt()->format('Y-m-d');
            } elseif ($post->getCreatedAt()) {
                $lastmod = $post->getCreatedAt()->format('Y-m-d');
            }
            $this->writeUrl($xml, $loc, $lastmod, 'monthly', '0.7');
        }

        $xml->endElement(); // urlset

        $content = $xml->outputMemory(true);

        $publicDir = $this->projectDir . '/public';
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }

        $file = $publicDir . '/sitemap.xml';

        // If existing sitemap exists, create a timestamped backup
        if (file_exists($file)) {
            $bak = $file . '.bak.' . date('YmdHis');
            copy($file, $bak);
        }

        // write atomically
        $tmp = $file . '.tmp';
        file_put_contents($tmp, $content);
        // set permissions then rename
        @chmod($tmp, 0644);
        rename($tmp, $file);
    }

    private function writeUrl(\XMLWriter $xml, string $loc, ?string $lastmod = null, string $changefreq = 'monthly', string $priority = '0.5'): void
    {
        $xml->startElement('url');
        $xml->writeElement('loc', $loc);
        if ($lastmod) {
            $xml->writeElement('lastmod', $lastmod);
        }
        $xml->writeElement('changefreq', $changefreq);
        $xml->writeElement('priority', $priority);
        $xml->endElement();
    }

    private function makeAbsoluteUrl(string $path): string
    {
        // If path already absolute, return as-is
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }

        // Ensure path starts with '/'
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . ltrim($path, '/');
        }

        return $this->baseUrl . $path;
    }
}
