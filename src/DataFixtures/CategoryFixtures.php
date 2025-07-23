<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            [
                'name' => 'Développement Web',
                'slug' => 'developpement-web',
                'description' => 'Articles techniques et tutoriels sur les technologies de développement web, les meilleures pratiques, et les nouvelles tendances.'
            ],
            [
                'name' => 'Projets Clients',
                'slug' => 'projets-clients',
                'description' => 'Études de cas et retours d\'expérience sur nos projets clients, transformations digitales réussies.'
            ],
            [
                'name' => 'Conseils aux Entreprises',
                'slug' => 'conseils-aux-entreprises',
                'description' => 'Articles offrant des conseils pratiques pour les entreprises sur la planification, la conception, et le développement de leurs sites web.'
            ],
            [
                'name' => 'Marketing Digital et Stratégie Web',
                'slug' => 'marketing-digital-et-strategie-web',
                'description' => 'Conseils et stratégies pour améliorer la visibilité en ligne et attirer plus de clients.'
            ],
            [
                'name' => 'Design et Expérience Utilisateur (UX)',
                'slug' => 'design-et-experience-utilisateur-ux',
                'description' => 'Principes de design, ergonomie web et optimisation de l\'expérience utilisateur.'
            ],
            [
                'name' => 'Fonctionnalités et Performances Web',
                'slug' => 'fonctionnalites-et-performances-web',
                'description' => 'Optimisation des performances, nouvelles fonctionnalités web et techniques d\'amélioration.'
            ],
            [
                'name' => 'Sécurité et Conformité',
                'slug' => 'securite-et-conformite',
                'description' => 'Sécurité web, protection des données, RGPD et bonnes pratiques de sécurité.'
            ],
            [
                'name' => 'Transformation Numérique',
                'slug' => 'transformation-numerique',
                'description' => 'Accompagnement dans la transformation digitale des entreprises et modernisation des processus.'
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = new Categories();
            $category->setName($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setDescription($categoryData['description']);
            $category->setCreatedAt(new \DateTime());

            $manager->persist($category);
            
            // Référence pour les posts
            $this->addReference('category-' . $categoryData['slug'], $category);
        }

        $manager->flush();
    }
}
