<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 *
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

        public function findLatestPostsByCategory(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Posts p
            INNER JOIN p.categories c
            WHERE p.createdAt IN (
                SELECT MAX(p2.createdAt)
                FROM App\Entity\Posts p2
                INNER JOIN p2.categories c2
                WHERE c2.id = c.id
                GROUP BY c2.id
            )'
        );

        return $query->getResult();
    }

    public function searchByKeyword($keyword)
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :keyword OR p.content LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllTitles(): array
    {
        // Effectue la requête pour récupérer les titres
        $query = $this->createQueryBuilder('p')
            ->select('p.title')
            ->getQuery();

        // Exécute la requête et retourne le tableau des titres
        $result = $query->getResult();

        // Convertir le résultat pour ne récupérer que les titres sous forme de tableau de chaînes
        return array_map(function ($post) {
            return $post['title'];
        }, $result);
    }

    public function findTitlesByCategory(string $categoryName): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c') // Assure-toi que 'category' est le bon nom de la relation dans Posts
            ->andWhere('c.name = :categoryName') // Utilise la propriété 'name' de Categories
            ->setParameter('categoryName', $categoryName)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les articles publiés, triés par date de création décroissante
     */
    public function findPublishedPosts(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Trouve un article publié par son slug
     */
    public function findPublishedBySlug(string $slug): ?Posts
    {
        return $this->createQueryBuilder('p')
            ->where('p.slug = :slug')
            ->andWhere('p.isPublished = :published')
            ->setParameter('slug', $slug)
            ->setParameter('published', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les articles par catégorie
     */
    public function findByCategory(string $categorySlug, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.slug = :categorySlug')
            ->andWhere('p.isPublished = :published')
            ->setParameter('categorySlug', $categorySlug)
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Trouve les articles favoris
     */
    public function findFavoritePosts(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.isFavorite = :favorite')
            ->andWhere('p.isPublished = :published')
            ->setParameter('favorite', true)
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Trouve les 3 articles les plus récents
     */
    public function findThreeMostRecentPosts(): array
    {
        return $this->findPublishedPosts(3);
    }

    /**
     * Recherche d'articles par terme
     */
    public function searchPosts(string $searchTerm): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :searchTerm OR p.content LIKE :searchTerm')
            ->andWhere('p.isPublished = :published')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre total d'articles publiés
     */
    public function countPublishedPosts(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.isPublished = :published')
            ->setParameter('published', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
