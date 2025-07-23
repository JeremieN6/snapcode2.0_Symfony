<?php

namespace App\Repository;

use App\Entity\Keywords;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Keywords>
 *
 * @method Keywords|null find($id, $lockMode = null, $lockVersion = null)
 * @method Keywords|null findOneBy(array $criteria, array $orderBy = null)
 * @method Keywords[]    findAll()
 * @method Keywords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeywordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Keywords::class);
    }

    /**
     * Trouve tous les mots-clés avec le nombre d'articles associés
     */
    public function findAllWithPostCount(): array
    {
        return $this->createQueryBuilder('k')
            ->select('k.id', 'k.name', 'k.slug', 'COUNT(p.id) AS postCount')
            ->leftJoin('k.posts', 'p', 'WITH', 'p.isPublished = :published')
            ->setParameter('published', true)
            ->groupBy('k.id')
            ->orderBy('k.name', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Trouve un mot-clé par son slug
     */
    public function findBySlug(string $slug): ?Keywords
    {
        return $this->createQueryBuilder('k')
            ->where('k.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les mots-clés les plus utilisés
     */
    public function findMostUsed(int $limit = 10): array
    {
        return $this->createQueryBuilder('k')
            ->select('k', 'COUNT(p.id) AS postCount')
            ->leftJoin('k.posts', 'p', 'WITH', 'p.isPublished = :published')
            ->setParameter('published', true)
            ->groupBy('k.id')
            ->orderBy('postCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
