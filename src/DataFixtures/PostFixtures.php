<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur par défaut s'il n'existe pas
        $user = $manager->getRepository(Users::class)->findOneBy(['email' => 'admin@snapcode.fr']);
        if (!$user) {
            $user = new Users();
            $user->setEmail('admin@snapcode.fr');
            $user->setPrenom('Jérémie');
            $user->setNom('NGOYI');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword('$2y$13$hashed_password'); // Mot de passe hashé
            $manager->persist($user);
        }

        $posts = [
            [
                'title' => 'Les Clés du Digital : résumé, avis, et applications concrètes',
                'slug' => 'les-cles-du-digital-resume-avis-et-applications-concretes',
                'content' => '<p>Dans un monde où la transformation numérique redéfinit les règles du jeu, comprendre les clés du digital devient essentiel pour toute entreprise souhaitant rester compétitive.</p><p>Cet article explore les stratégies digitales qui fonctionnent vraiment et comment les appliquer concrètement dans votre entreprise.</p>',
                'category' => 'conseils-aux-entreprises',
                'featuredImage' => 'https://images.pexels.com/photos/9002742/pexels-photo-9002742.jpeg?auto=compress&cs=tinysrgb&w=600'
            ],
            [
                'title' => 'Comment les chatbots révolutionnent les voyages : 5 exemples concrets',
                'slug' => 'comment-les-chatbots-revolutionnent-les-voyages-5-exemples-concrets',
                'content' => '<p>L\'industrie du voyage connaît une révolution silencieuse grâce aux chatbots et à l\'intelligence artificielle.</p><p>Découvrez 5 exemples concrets de comment ces technologies transforment l\'expérience client dans le secteur du tourisme.</p>',
                'category' => 'developpement-web',
                'featuredImage' => 'https://images.pexels.com/photos/31612374/pexels-photo-31612374/free-photo-of-vue-aerienne-de-bateaux-sur-les-eaux-turquoise-de-la-cote.jpeg?auto=compress&cs=tinysrgb&w=600'
            ],
            [
                'title' => 'Les réseaux sociaux pour votre entreprise ? A l\'opposé de l\'opinion publique',
                'slug' => 'les-reseaux-sociaux-a-loppose-de-lopinion-publique-pour-votre-entreprise',
                'content' => '<p>Contrairement aux idées reçues, les réseaux sociaux ne sont pas toujours la solution miracle pour votre entreprise.</p><p>Analyse critique et conseils pratiques pour une stratégie social media réellement efficace.</p>',
                'category' => 'marketing-digital-et-strategie-web',
                'featuredImage' => 'https://images.pexels.com/photos/7676406/pexels-photo-7676406.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
            ],
            [
                'title' => 'Comment un restaurant a boosté son activité grâce au digital',
                'slug' => 'comment-un-restaurant-a-booste-son-activite-grace-au-digital',
                'content' => '<p>Étude de cas complète : comment un petit restaurant local a multiplié son chiffre d\'affaires par 3 grâce à une stratégie digitale bien pensée.</p><p>Découvrez les outils et techniques utilisés, reproductibles pour votre propre business.</p>',
                'category' => 'projets-clients',
                'featuredImage' => 'https://images.pexels.com/photos/67468/pexels-photo-67468.jpeg?auto=compress&cs=tinysrgb&w=600'
            ],
            [
                'title' => 'Pourquoi l\'expérience mobile-first est essentielle pour votre site en 2025',
                'slug' => 'pourquoi-lexperience-mobile-first-est-essentielle-pour-votre-site-en-2025',
                'content' => '<p>Avec plus de 60% du trafic web provenant des mobiles, adopter une approche mobile-first n\'est plus une option mais une nécessité.</p><p>Guide complet pour optimiser votre site pour l\'expérience mobile.</p>',
                'category' => 'design-et-experience-utilisateur-ux',
                'featuredImage' => 'https://images.pexels.com/photos/5082579/pexels-photo-5082579.jpeg?auto=compress&cs=tinysrgb&w=600'
            ]
        ];

        foreach ($posts as $postData) {
            $post = new Posts();
            $post->setTitle($postData['title']);
            $post->setSlug($postData['slug']);
            $post->setContent($postData['content']);
            $post->setFeaturedImage($postData['featuredImage']);
            $post->setUsers($user);
            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());

            // Associer la catégorie
            $category = $this->getReference('category-' . $postData['category']);
            $post->addCategory($category);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
