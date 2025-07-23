<?php

namespace App\Command;

use App\Repository\PostsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:seo-check',
    description: 'Vérifie l\'optimisation SEO du blog',
)]
class SeoCheckCommand extends Command
{
    public function __construct(
        private PostsRepository $postsRepository,
        private CategoriesRepository $categoriesRepository,
        private UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Audit SEO du Blog SnapCode™ Agency');

        $score = 0;
        $maxScore = 0;
        $recommendations = [];

        // 1. Vérification des articles
        $io->section('📝 Analyse des articles');
        
        $posts = $this->postsRepository->findPublishedPosts();
        $postsCount = count($posts);
        
        $io->text("Nombre d'articles publiés: $postsCount");
        
        if ($postsCount > 0) {
            $score += 10;
            $io->text('✅ Vous avez du contenu publié');
        } else {
            $recommendations[] = 'Publier au moins 5 articles de qualité pour améliorer le SEO';
        }
        $maxScore += 10;

        // 2. Vérification des métadonnées
        $io->section('🏷️ Analyse des métadonnées');
        
        $postsWithMetaTitle = 0;
        $postsWithMetaDescription = 0;
        $postsWithGoodTitleLength = 0;
        $postsWithGoodDescLength = 0;
        
        foreach ($posts as $post) {
            if (!empty($post->getMetaTitle())) {
                $postsWithMetaTitle++;
                if (strlen($post->getMetaTitle()) <= 60) {
                    $postsWithGoodTitleLength++;
                }
            }
            
            if (!empty($post->getMetaDescription())) {
                $postsWithMetaDescription++;
                if (strlen($post->getMetaDescription()) <= 160) {
                    $postsWithGoodDescLength++;
                }
            }
        }
        
        if ($postsCount > 0) {
            $metaTitlePercent = round(($postsWithMetaTitle / $postsCount) * 100);
            $metaDescPercent = round(($postsWithMetaDescription / $postsCount) * 100);
            
            $io->text([
                "Articles avec meta title: $postsWithMetaTitle/$postsCount ($metaTitlePercent%)",
                "Articles avec meta description: $postsWithMetaDescription/$postsCount ($metaDescPercent%)",
                "Titles de bonne longueur (≤60 car): $postsWithGoodTitleLength/$postsWithMetaTitle",
                "Descriptions de bonne longueur (≤160 car): $postsWithGoodDescLength/$postsWithMetaDescription"
            ]);
            
            if ($metaTitlePercent >= 90) {
                $score += 15;
                $io->text('✅ Excellente couverture des meta titles');
            } elseif ($metaTitlePercent >= 70) {
                $score += 10;
                $io->text('⚠️ Bonne couverture des meta titles');
                $recommendations[] = 'Ajouter des meta titles aux articles manquants';
            } else {
                $score += 5;
                $recommendations[] = 'Ajouter des meta titles à tous les articles (crucial pour le SEO)';
            }
            
            if ($metaDescPercent >= 90) {
                $score += 15;
                $io->text('✅ Excellente couverture des meta descriptions');
            } elseif ($metaDescPercent >= 70) {
                $score += 10;
                $io->text('⚠️ Bonne couverture des meta descriptions');
                $recommendations[] = 'Ajouter des meta descriptions aux articles manquants';
            } else {
                $score += 5;
                $recommendations[] = 'Ajouter des meta descriptions à tous les articles';
            }
        }
        $maxScore += 30;

        // 3. Vérification des slugs
        $io->section('🔗 Analyse des URLs');
        
        $postsWithSlug = 0;
        $slugsWithGoodLength = 0;
        
        foreach ($posts as $post) {
            if (!empty($post->getSlug())) {
                $postsWithSlug++;
                if (strlen($post->getSlug()) <= 60 && strlen($post->getSlug()) >= 10) {
                    $slugsWithGoodLength++;
                }
            }
        }
        
        if ($postsCount > 0) {
            $slugPercent = round(($postsWithSlug / $postsCount) * 100);
            $io->text("Articles avec slug: $postsWithSlug/$postsCount ($slugPercent%)");
            
            if ($slugPercent === 100) {
                $score += 10;
                $io->text('✅ Tous les articles ont des URLs optimisées');
            } else {
                $recommendations[] = 'Vérifier que tous les articles ont des slugs SEO-friendly';
            }
        }
        $maxScore += 10;

        // 4. Vérification des catégories
        $io->section('📂 Analyse des catégories');
        
        $categories = $this->categoriesRepository->findAll();
        $categoriesCount = count($categories);
        
        $postsWithCategories = 0;
        foreach ($posts as $post) {
            if ($post->getCategories()->count() > 0) {
                $postsWithCategories++;
            }
        }
        
        $io->text("Nombre de catégories: $categoriesCount");
        
        if ($postsCount > 0) {
            $catPercent = round(($postsWithCategories / $postsCount) * 100);
            $io->text("Articles catégorisés: $postsWithCategories/$postsCount ($catPercent%)");
            
            if ($catPercent >= 95) {
                $score += 10;
                $io->text('✅ Excellente organisation par catégories');
            } elseif ($catPercent >= 80) {
                $score += 7;
                $recommendations[] = 'Catégoriser tous les articles pour une meilleure organisation';
            } else {
                $score += 3;
                $recommendations[] = 'Créer des catégories et organiser tous les articles';
            }
        }
        $maxScore += 10;

        // 5. Vérification des mots-clés
        $io->section('🏷️ Analyse des mots-clés');
        
        $postsWithKeywords = 0;
        foreach ($posts as $post) {
            if ($post->getKeywords()->count() > 0) {
                $postsWithKeywords++;
            }
        }
        
        if ($postsCount > 0) {
            $keywordPercent = round(($postsWithKeywords / $postsCount) * 100);
            $io->text("Articles avec mots-clés: $postsWithKeywords/$postsCount ($keywordPercent%)");
            
            if ($keywordPercent >= 80) {
                $score += 10;
                $io->text('✅ Bon usage des mots-clés');
            } else {
                $score += 5;
                $recommendations[] = 'Ajouter des mots-clés pertinents à tous les articles';
            }
        }
        $maxScore += 10;

        // 6. Vérification du contenu
        $io->section('📄 Analyse du contenu');
        
        $postsWithGoodContent = 0;
        $totalContentLength = 0;
        
        foreach ($posts as $post) {
            $contentLength = strlen(strip_tags($post->getContent() ?? ''));
            $totalContentLength += $contentLength;
            
            if ($contentLength >= 300) {
                $postsWithGoodContent++;
            }
        }
        
        if ($postsCount > 0) {
            $avgContentLength = round($totalContentLength / $postsCount);
            $goodContentPercent = round(($postsWithGoodContent / $postsCount) * 100);
            
            $io->text([
                "Longueur moyenne du contenu: $avgContentLength caractères",
                "Articles avec contenu suffisant (≥300 car): $postsWithGoodContent/$postsCount ($goodContentPercent%)"
            ]);
            
            if ($avgContentLength >= 800) {
                $score += 15;
                $io->text('✅ Excellent contenu détaillé');
            } elseif ($avgContentLength >= 500) {
                $score += 10;
                $io->text('✅ Bon contenu');
            } else {
                $score += 5;
                $recommendations[] = 'Créer du contenu plus détaillé (minimum 500 mots par article)';
            }
        }
        $maxScore += 15;

        // 7. Vérification technique
        $io->section('⚙️ Vérifications techniques');
        
        // Vérifier le sitemap
        try {
            $sitemapUrl = $this->urlGenerator->generate('sitemap', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $score += 5;
            $io->text("✅ Sitemap disponible: $sitemapUrl");
        } catch (\Exception $e) {
            $recommendations[] = 'Vérifier que le sitemap.xml est accessible';
        }
        $maxScore += 5;

        // Vérifier robots.txt
        if (file_exists('public/robots.txt')) {
            $score += 5;
            $io->text('✅ Fichier robots.txt présent');
        } else {
            $recommendations[] = 'Créer un fichier robots.txt optimisé';
        }
        $maxScore += 5;

        // Calcul du score final
        $finalScore = round(($score / $maxScore) * 100);
        
        $io->section('📊 Résultats de l\'audit');
        
        $io->text("Score SEO: $score/$maxScore points ($finalScore%)");
        
        if ($finalScore >= 90) {
            $io->success('🎉 Excellent ! Votre blog est très bien optimisé pour le SEO');
        } elseif ($finalScore >= 70) {
            $io->note('👍 Bon travail ! Quelques améliorations peuvent encore être apportées');
        } elseif ($finalScore >= 50) {
            $io->warning('⚠️ Votre blog a besoin d\'optimisations SEO importantes');
        } else {
            $io->error('❌ Votre blog nécessite des améliorations SEO critiques');
        }

        // Recommandations
        if (!empty($recommendations)) {
            $io->section('💡 Recommandations d\'amélioration');
            $io->listing($recommendations);
        }

        // Conseils généraux
        $io->section('📋 Conseils SEO généraux');
        $io->text([
            '• Publiez régulièrement du contenu de qualité (minimum 1 article/semaine)',
            '• Utilisez des mots-clés pertinents dans vos titres et contenus',
            '• Créez des liens internes entre vos articles',
            '• Optimisez vos images avec des attributs alt descriptifs',
            '• Partagez vos articles sur les réseaux sociaux',
            '• Encouragez les commentaires et interactions',
            '• Surveillez vos performances dans Google Search Console'
        ]);

        return Command::SUCCESS;
    }
}
