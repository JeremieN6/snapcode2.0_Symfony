<?php

namespace App\Command;

use App\Repository\PostsRepository;
use App\Repository\CategoriesRepository;
use App\Repository\KeywordsRepository;
use App\Repository\CommentsRepository;
use App\Service\SitemapService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:test-blog-integration',
    description: 'Teste l\'intégration du blog et valide les fonctionnalités',
)]
class TestBlogIntegrationCommand extends Command
{
    public function __construct(
        private PostsRepository $postsRepository,
        private CategoriesRepository $categoriesRepository,
        private KeywordsRepository $keywordsRepository,
        private CommentsRepository $commentsRepository,
        private SitemapService $sitemapService,
        private UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Test de l\'intégration du blog');

        $errors = [];
        $warnings = [];

        // Test 1: Vérifier les entités
        $io->section('1. Vérification des entités');
        
        $postsCount = $this->postsRepository->countPublishedPosts();
        $categoriesCount = count($this->categoriesRepository->findAll());
        $keywordsCount = count($this->keywordsRepository->findAll());
        
        $io->text([
            "Articles publiés: $postsCount",
            "Catégories: $categoriesCount", 
            "Mots-clés: $keywordsCount"
        ]);

        if ($postsCount === 0) {
            $warnings[] = "Aucun article publié trouvé";
        }
        if ($categoriesCount === 0) {
            $warnings[] = "Aucune catégorie trouvée";
        }

        // Test 2: Vérifier les relations
        $io->section('2. Vérification des relations');
        
        $posts = $this->postsRepository->findPublishedPosts(5);
        foreach ($posts as $post) {
            if ($post->getCategories()->count() === 0) {
                $warnings[] = "L'article '{$post->getTitle()}' n'a pas de catégorie";
            }
            if (!$post->getUsers()) {
                $errors[] = "L'article '{$post->getTitle()}' n'a pas d'auteur";
            }
            if (empty($post->getSlug())) {
                $errors[] = "L'article '{$post->getTitle()}' n'a pas de slug";
            }
        }

        // Test 3: Vérifier les URLs
        $io->section('3. Vérification des URLs');
        
        try {
            $blogIndexUrl = $this->urlGenerator->generate('blog_index');
            $io->text("URL index blog: $blogIndexUrl");
            
            if (!empty($posts)) {
                $firstPost = $posts[0];
                $postUrl = $this->urlGenerator->generate('blog_index', ['slug' => $firstPost->getSlug()]);
                $io->text("URL premier article: $postUrl");
            }
            
            $categories = $this->categoriesRepository->findAll();
            if (!empty($categories)) {
                $firstCategory = $categories[0];
                $categoryUrl = $this->urlGenerator->generate('blog_category', ['slug' => $firstCategory->getSlug()]);
                $io->text("URL première catégorie: $categoryUrl");
            }
        } catch (\Exception $e) {
            $errors[] = "Erreur génération URL: " . $e->getMessage();
        }

        // Test 4: Vérifier le sitemap
        $io->section('4. Vérification du sitemap');
        
        try {
            $sitemapContent = $this->sitemapService->generateSitemap();
            $sitemapSize = strlen($sitemapContent);
            $io->text("Sitemap généré: $sitemapSize caractères");
            
            // Vérifier que le sitemap contient les URLs du blog
            if (strpos($sitemapContent, '/blog/') === false) {
                $warnings[] = "Le sitemap ne semble pas contenir d'URLs de blog";
            }
        } catch (\Exception $e) {
            $errors[] = "Erreur génération sitemap: " . $e->getMessage();
        }

        // Test 5: Vérifier les métadonnées SEO
        $io->section('5. Vérification SEO');
        
        foreach ($posts as $post) {
            if (empty($post->getMetaTitle())) {
                $warnings[] = "L'article '{$post->getTitle()}' n'a pas de meta title";
            }
            if (empty($post->getMetaDescription())) {
                $warnings[] = "L'article '{$post->getTitle()}' n'a pas de meta description";
            }
            if (strlen($post->getMetaTitle() ?? '') > 60) {
                $warnings[] = "Le meta title de '{$post->getTitle()}' est trop long (>60 caractères)";
            }
            if (strlen($post->getMetaDescription() ?? '') > 160) {
                $warnings[] = "La meta description de '{$post->getTitle()}' est trop longue (>160 caractères)";
            }
        }

        // Test 6: Vérifier les fonctionnalités de recherche
        $io->section('6. Vérification de la recherche');
        
        try {
            $searchResults = $this->postsRepository->searchPosts('développement');
            $io->text("Résultats recherche 'développement': " . count($searchResults));
        } catch (\Exception $e) {
            $errors[] = "Erreur recherche: " . $e->getMessage();
        }

        // Test 7: Vérifier les catégories avec compteurs
        $io->section('7. Vérification des compteurs');
        
        try {
            $categoriesWithCount = $this->categoriesRepository->findAllWithPostCount();
            $io->text("Catégories avec compteurs: " . count($categoriesWithCount));
            
            foreach ($categoriesWithCount as $category) {
                if ($category['postCount'] < 0) {
                    $errors[] = "Compteur négatif pour la catégorie: " . $category['name'];
                }
            }
        } catch (\Exception $e) {
            $errors[] = "Erreur compteurs catégories: " . $e->getMessage();
        }

        // Résultats
        $io->section('Résultats des tests');

        if (empty($errors) && empty($warnings)) {
            $io->success('Tous les tests sont passés avec succès ! 🎉');
            $io->note('Le blog est prêt à être utilisé.');
        } else {
            if (!empty($errors)) {
                $io->error('Erreurs critiques détectées:');
                $io->listing($errors);
            }
            
            if (!empty($warnings)) {
                $io->warning('Avertissements:');
                $io->listing($warnings);
            }
        }

        // Instructions pour la suite
        $io->section('Prochaines étapes recommandées');
        $io->text([
            '1. Exécuter la migration de base de données:',
            '   php bin/console doctrine:migrations:migrate',
            '',
            '2. Importer les données d\'exemple:',
            '   php bin/console app:migrate-blog-data',
            '',
            '3. Vérifier les URLs dans le navigateur:',
            '   - /blog (index du blog)',
            '   - /blog/[slug-article] (page article)',
            '   - /blog/category/[slug-categorie] (page catégorie)',
            '   - /sitemap.xml (sitemap)',
            '',
            '4. Tester l\'interface d\'administration:',
            '   - /admin (tableau de bord)',
            '   - Créer/modifier des articles',
            '   - Gérer les catégories et mots-clés',
            '',
            '5. Optimisations SEO:',
            '   - Vérifier les balises meta sur chaque page',
            '   - Tester les partages sociaux',
            '   - Valider le sitemap dans Google Search Console'
        ]);

        return empty($errors) ? Command::SUCCESS : Command::FAILURE;
    }
}
