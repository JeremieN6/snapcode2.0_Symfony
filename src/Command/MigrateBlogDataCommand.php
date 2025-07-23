<?php

namespace App\Command;

use App\Entity\Posts;
use App\Entity\Categories;
use App\Entity\Keywords;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:migrate-blog-data',
    description: 'Migre les données du blog depuis l\'ancien système vers le nouveau',
)]
class MigrateBlogDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Migration des données du blog');

        // Vérifier si l'utilisateur admin existe
        $userRepository = $this->entityManager->getRepository(Users::class);
        $adminUser = $userRepository->findOneBy(['email' => 'admin@jeremiecode.fr']);
        
        if (!$adminUser) {
            // Créer un utilisateur admin par défaut
            $adminUser = new Users();
            $adminUser->setEmail('admin@jeremiecode.fr');
            $adminUser->setNom('Admin');
            $adminUser->setPrenom('SnapCode');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword('$2y$13$hashed_password'); // Vous devrez changer cela
            $adminUser->setIsVerified(true);
            
            $this->entityManager->persist($adminUser);
            $io->note('Utilisateur admin créé');
        }

        // Créer des catégories d'exemple
        $categories = [
            [
                'name' => 'Développement Web',
                'slug' => 'developpement-web',
                'description' => 'Articles sur le développement web, les frameworks et les bonnes pratiques'
            ],
            [
                'name' => 'SEO',
                'slug' => 'seo',
                'description' => 'Conseils et techniques pour optimiser le référencement naturel'
            ],
            [
                'name' => 'Landing Pages',
                'slug' => 'landing-pages',
                'description' => 'Création et optimisation de pages d\'atterrissage efficaces'
            ],
            [
                'name' => 'Symfony',
                'slug' => 'symfony',
                'description' => 'Tutoriels et astuces sur le framework Symfony'
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'Développement JavaScript moderne et frameworks'
            ]
        ];

        $categoryEntities = [];
        foreach ($categories as $categoryData) {
            $category = new Categories();
            $category->setName($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setDescription($categoryData['description']);
            
            $this->entityManager->persist($category);
            $categoryEntities[] = $category;
        }

        // Créer des mots-clés d'exemple
        $keywords = [
            'PHP', 'Symfony', 'JavaScript', 'HTML', 'CSS', 'SEO', 'Landing Page',
            'Responsive Design', 'Performance', 'UX/UI', 'React', 'Vue.js',
            'Bootstrap', 'Tailwind CSS', 'MySQL', 'PostgreSQL', 'Git'
        ];

        $keywordEntities = [];
        foreach ($keywords as $keywordName) {
            $keyword = new Keywords();
            $keyword->setName($keywordName);
            $keyword->setSlug(strtolower(str_replace(['/', ' '], ['-', '-'], $keywordName)));
            
            $this->entityManager->persist($keyword);
            $keywordEntities[] = $keyword;
        }

        // Créer des articles d'exemple
        $posts = [
            [
                'title' => 'Comment créer une landing page efficace en 2024',
                'slug' => 'comment-creer-landing-page-efficace-2024',
                'content' => '<h2>Introduction</h2><p>Une landing page efficace est essentielle pour convertir vos visiteurs en clients. Chez SnapCode™ Agency, nous créons des pages d\'atterrissage qui génèrent des résultats concrets pour nos clients à Franconville et dans toute l\'Île-de-France.</p><h2>Les éléments clés d\'une landing page qui convertit</h2><h3>1. Un titre accrocheur et percutant</h3><p>Votre titre doit immédiatement communiquer la valeur de votre offre. Il doit être clair, concis et orienté bénéfice client.</p><h3>2. Une proposition de valeur unique</h3><p>Expliquez en quelques mots pourquoi votre solution est la meilleure. Qu\'est-ce qui vous différencie de la concurrence ?</p><h3>3. Un call-to-action visible et incitatif</h3><p>Votre bouton d\'action doit se démarquer visuellement et utiliser un verbe d\'action fort : "Obtenir mon devis gratuit", "Commencer maintenant".</p><h3>4. Des témoignages clients authentiques</h3><p>Les avis clients rassurent et prouvent la qualité de vos services. Incluez des photos et des détails spécifiques.</p><h3>5. Un design responsive et rapide</h3><p>Plus de 60% du trafic web provient du mobile. Votre landing page doit être parfaitement optimisée pour tous les appareils.</p><h2>Optimisation SEO de votre landing page</h2><p>Une landing page bien référencée attire plus de visiteurs qualifiés :</p><ul><li>Utilisez des mots-clés pertinents dans le titre et les sous-titres</li><li>Optimisez la meta description pour améliorer le taux de clic</li><li>Structurez votre contenu avec des balises H1, H2, H3</li><li>Ajoutez des données structurées pour les moteurs de recherche</li></ul><h2>Mesurer les performances</h2><p>Suivez ces métriques clés :</p><ul><li>Taux de conversion</li><li>Temps passé sur la page</li><li>Taux de rebond</li><li>Sources de trafic</li></ul><p>Chez SnapCode™ Agency, nous vous accompagnons dans la création et l\'optimisation de vos landing pages pour maximiser vos conversions.</p>',
                'metaTitle' => 'Landing Page Efficace 2024 : Guide Complet | SnapCode™ Agency',
                'metaDescription' => 'Découvrez comment créer une landing page qui convertit en 2024. Guide complet avec exemples et bonnes pratiques SEO par SnapCode™ Agency.',
                'categoryIndex' => 2, // Landing Pages
                'keywordIndexes' => [6, 9, 5], // Landing Page, UX/UI, SEO
                'isFavorite' => true
            ],
            [
                'title' => 'Symfony 6 : Les nouveautés à connaître',
                'slug' => 'symfony-6-nouveautes-a-connaitre',
                'content' => '<h2>Symfony 6 est arrivé !</h2><p>La nouvelle version majeure de Symfony apporte de nombreuses améliorations et nouvelles fonctionnalités.</p><h2>Les principales nouveautés</h2><ul><li>PHP 8.1 minimum requis</li><li>Amélioration des performances</li><li>Nouvelles fonctionnalités de sécurité</li><li>Simplification de la configuration</li></ul><h2>Migration depuis Symfony 5</h2><p>Le processus de migration est facilité grâce aux outils fournis par l\'équipe Symfony.</p>',
                'metaTitle' => 'Symfony 6 Nouveautés : Guide Migration | SnapCode™',
                'metaDescription' => 'Découvrez les nouveautés de Symfony 6 et comment migrer votre application. Guide complet pour développeurs.',
                'categoryIndex' => 3, // Symfony
                'keywordIndexes' => [0, 1], // PHP, Symfony
                'isFavorite' => false
            ],
            [
                'title' => 'SEO en 2024 : Les techniques qui fonctionnent vraiment',
                'slug' => 'seo-2024-techniques-qui-fonctionnent',
                'content' => '<h2>Le SEO évolue constamment</h2><p>En 2024, certaines techniques SEO restent incontournables tandis que d\'autres deviennent obsolètes.</p><h2>Les fondamentaux qui marchent</h2><ol><li>Contenu de qualité et original</li><li>Optimisation technique</li><li>Expérience utilisateur</li><li>Vitesse de chargement</li><li>Mobile-first</li></ol><h2>Les nouvelles tendances</h2><p>L\'IA et la recherche vocale changent la donne en SEO. Il faut adapter sa stratégie en conséquence.</p>',
                'metaTitle' => 'SEO 2024 : Techniques Efficaces pour Référencement | SnapCode™',
                'metaDescription' => 'Découvrez les techniques SEO qui fonctionnent en 2024. Guide pratique pour améliorer votre référencement naturel.',
                'categoryIndex' => 1, // SEO
                'keywordIndexes' => [5, 8, 9], // SEO, Performance, UX/UI
                'isFavorite' => true
            ],
            [
                'title' => 'JavaScript moderne : ES2024 et les bonnes pratiques',
                'slug' => 'javascript-moderne-es2024-bonnes-pratiques',
                'content' => '<h2>JavaScript continue d\'évoluer</h2><p>ES2024 apporte de nouvelles fonctionnalités qui améliorent l\'expérience de développement.</p><h2>Les nouveautés ES2024</h2><ul><li>Nouvelles méthodes pour les arrays</li><li>Amélioration des modules</li><li>Nouvelles APIs</li></ul><h2>Bonnes pratiques</h2><p>Utilisez les outils modernes comme ESLint, Prettier et TypeScript pour un code plus maintenable.</p>',
                'metaTitle' => 'JavaScript ES2024 : Nouveautés et Bonnes Pratiques | SnapCode™',
                'metaDescription' => 'Découvrez les nouveautés JavaScript ES2024 et les bonnes pratiques pour un développement moderne.',
                'categoryIndex' => 4, // JavaScript
                'keywordIndexes' => [2, 10, 11], // JavaScript, React, Vue.js
                'isFavorite' => false
            ],
            [
                'title' => 'Responsive Design : Guide complet pour 2024',
                'slug' => 'responsive-design-guide-complet-2024',
                'content' => '<h2>Le responsive design est essentiel</h2><p>Avec la diversité des appareils, créer des sites adaptatifs est crucial pour l\'expérience utilisateur.</p><h2>Les techniques modernes</h2><ul><li>CSS Grid et Flexbox</li><li>Media queries avancées</li><li>Images responsives</li><li>Typography fluide</li></ul><h2>Outils et frameworks</h2><p>Bootstrap, Tailwind CSS et les CSS custom properties facilitent la création de designs responsives.</p>',
                'metaTitle' => 'Responsive Design 2024 : Guide Complet CSS | SnapCode™',
                'metaDescription' => 'Maîtrisez le responsive design en 2024. Guide complet avec CSS Grid, Flexbox et bonnes pratiques.',
                'categoryIndex' => 0, // Développement Web
                'keywordIndexes' => [3, 4, 7, 12, 13], // HTML, CSS, Responsive Design, Bootstrap, Tailwind CSS
                'isFavorite' => false
            ],
            [
                'title' => 'SnapCode™ Agency : Votre partenaire digital à Franconville',
                'slug' => 'snapcode-agency-partenaire-digital-franconville',
                'content' => '<h2>Qui sommes-nous ?</h2><p>SnapCode™ Agency est une agence de développement web spécialisée dans la création de sites internet et de landing pages sur-mesure. Basée à Franconville dans le Val-d\'Oise, nous accompagnons les entreprises de toute l\'Île-de-France dans leur transformation digitale.</p><h2>Nos services</h2><h3>Création de sites internet</h3><p>Nous concevons des sites web modernes, responsives et optimisés pour le référencement naturel. Que vous soyez une PME, un artisan ou une startup, nous adaptons nos solutions à vos besoins et votre budget.</p><h3>Landing pages haute conversion</h3><p>Nos pages d\'atterrissage sont conçues pour convertir vos visiteurs en clients. Nous utilisons les meilleures pratiques UX/UI et les dernières techniques de persuasion digitale.</p><h3>Optimisation SEO</h3><p>Un beau site ne sert à rien s\'il n\'est pas visible. Nous optimisons votre présence en ligne pour améliorer votre positionnement sur Google et attirer plus de clients qualifiés.</p><h2>Pourquoi choisir SnapCode™ Agency ?</h2><ul><li><strong>Expertise technique</strong> : Maîtrise des technologies modernes (Symfony, React, etc.)</li><li><strong>Approche personnalisée</strong> : Chaque projet est unique et mérite une solution sur-mesure</li><li><strong>Proximité géographique</strong> : Basés à Franconville, nous sommes proches de nos clients</li><li><strong>Suivi post-livraison</strong> : Maintenance et évolutions de votre site</li><li><strong>Transparence</strong> : Devis clairs et communication régulière</li></ul><h2>Notre zone d\'intervention</h2><p>Bien que basés à Franconville, nous intervenons dans toute l\'Île-de-France : Paris, Hauts-de-Seine, Seine-Saint-Denis, Val-de-Marne, Seine-et-Marne, Yvelines, Essonne et Val-d\'Oise.</p><h2>Contactez-nous</h2><p>Vous avez un projet web ? Parlons-en ! Nous offrons un premier rendez-vous gratuit pour analyser vos besoins et vous proposer la meilleure solution.</p>',
                'metaTitle' => 'SnapCode™ Agency - Création Sites Web Franconville | Agence Digital',
                'metaDescription' => 'Agence web à Franconville spécialisée en création de sites internet et landing pages. Développement sur-mesure, SEO et accompagnement digital.',
                'categoryIndex' => 0, // Développement Web
                'keywordIndexes' => [6, 5, 9], // Landing Page, SEO, UX/UI
                'isFavorite' => true
            ]
        ];

        foreach ($posts as $postData) {
            $post = new Posts();
            $post->setTitle($postData['title']);
            $post->setSlug($postData['slug']);
            $post->setContent($postData['content']);
            $post->setMetaTitle($postData['metaTitle']);
            $post->setMetaDescription($postData['metaDescription']);
            $post->setUsers($adminUser);
            $post->setIsPublished(true);
            $post->setIsFavorite($postData['isFavorite']);
            
            // Ajouter la catégorie
            if (isset($categoryEntities[$postData['categoryIndex']])) {
                $post->addCategory($categoryEntities[$postData['categoryIndex']]);
            }
            
            // Ajouter les mots-clés
            foreach ($postData['keywordIndexes'] as $keywordIndex) {
                if (isset($keywordEntities[$keywordIndex])) {
                    $post->addKeyword($keywordEntities[$keywordIndex]);
                }
            }
            
            $this->entityManager->persist($post);
        }

        // Sauvegarder toutes les entités
        $this->entityManager->flush();

        $io->success('Migration terminée avec succès !');
        $io->note('Données créées :');
        $io->listing([
            count($categories) . ' catégories',
            count($keywords) . ' mots-clés',
            count($posts) . ' articles',
            '1 utilisateur admin'
        ]);

        return Command::SUCCESS;
    }
}
