<?php

namespace App\Repository;

use App\Entity\MoreInfoFormulaireController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoreInfoFormulaireController>
 *
 * @method MoreInfoFormulaireController|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoreInfoFormulaireController|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoreInfoFormulaireController[]    findAll()
 * @method MoreInfoFormulaireController[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoreInfoFormulaireControllerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoreInfoFormulaireController::class);
    }

//    /**
//     * @return MoreInfoFormulaireController[] Returns an array of MoreInfoFormulaireController objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MoreInfoFormulaireController
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
