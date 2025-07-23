<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * Trouve les commentaires approuvés pour un article
     */
    public function findApprovedByPost(Posts $post): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.posts = :post')
            ->andWhere('c.isApproved = :approved')
            ->andWhere('c.parent IS NULL') // Seulement les commentaires principaux
            ->setParameter('post', $post)
            ->setParameter('approved', true)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réponses approuvées d'un commentaire
     */
    public function findApprovedReplies(Comments $comment): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent = :parent')
            ->andWhere('c.isApproved = :approved')
            ->setParameter('parent', $comment)
            ->setParameter('approved', true)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les commentaires en attente de modération
     */
    public function findPendingComments(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isApproved = :approved')
            ->setParameter('approved', false)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre de commentaires approuvés pour un article
     */
    public function countApprovedByPost(Posts $post): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.posts = :post')
            ->andWhere('c.isApproved = :approved')
            ->setParameter('post', $post)
            ->setParameter('approved', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
