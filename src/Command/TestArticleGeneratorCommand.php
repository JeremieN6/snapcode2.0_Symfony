<?php

namespace App\Command;

use App\Service\BlogArticleGeneratorService;
use App\Repository\CategoriesRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-article-generator',
    description: 'Teste le générateur d\'articles automatique'
)]
class TestArticleGeneratorCommand extends Command
{
    public function __construct(
        private BlogArticleGeneratorService $articleGeneratorService,
        private CategoriesRepository $categoriesRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🧪 Test du Générateur d\'Articles IA');

        // Vérifier les catégories disponibles
        $categories = $this->categoriesRepository->findAll();
        
        if (empty($categories)) {
            $io->error('Aucune catégorie trouvée. Créez d\'abord des catégories.');
            return Command::FAILURE;
        }

        $io->section('📋 Catégories disponibles :');
        foreach ($categories as $category) {
            $io->text("• {$category->getName()} (ID: {$category->getId()})");
        }

        // Demander le sujet
        $subject = $io->ask('Entrez le sujet de l\'article à générer', 'Comment optimiser son site web pour le SEO');

        // Demander la catégorie
        $categoryChoices = [];
        foreach ($categories as $category) {
            $categoryChoices[$category->getName()] = $category->getId();
        }
        
        $selectedCategoryName = $io->choice('Choisissez une catégorie', array_keys($categoryChoices));
        $selectedCategoryId = $categoryChoices[$selectedCategoryName];

        $io->section('🚀 Génération de l\'article...');
        $io->text("Sujet : $subject");
        $io->text("Catégorie : $selectedCategoryName");
        $io->text("Catégorie ID : $selectedCategoryId");

        try {
            $startTime = microtime(true);
            
            $article = $this->articleGeneratorService->generateArticle($subject, $selectedCategoryId);
            
            $endTime = microtime(true);
            $generationTime = round($endTime - $startTime, 2);

            $io->success([
                '✅ Article généré avec succès !',
                "📝 Titre : {$article->getTitle()}",
                "🔗 Slug : {$article->getSlug()}",
                "⏱️  Temps de génération : {$generationTime} secondes",
                "📊 Longueur : " . str_word_count(strip_tags($article->getContent())) . " mots"
            ]);

            $io->section('📄 Aperçu du contenu :');
            $content = strip_tags($article->getContent());
            $preview = substr($content, 0, 300) . '...';
            $io->text($preview);

            $io->section('🔗 Liens utiles :');
            $io->listing([
                "Voir l'article : /blog/article/{$article->getSlug()}",
                "Modifier l'article : /admin/posts/{$article->getId()}/edit",
                "Générateur web : /admin/article-generator"
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error([
                '❌ Erreur lors de la génération :',
                $e->getMessage()
            ]);
            
            $io->section('🔧 Suggestions de dépannage :');
            $io->listing([
                'Vérifiez que OPENAI_API_KEY est défini dans .env.local',
                'Vérifiez votre connexion internet',
                'Assurez-vous d\'avoir des crédits OpenAI disponibles',
                'Vérifiez les logs dans var/log/'
            ]);

            return Command::FAILURE;
        }
    }
}
