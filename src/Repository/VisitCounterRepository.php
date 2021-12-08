<?php

namespace App\Repository;

use App\Entity\VisitCounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VisitCounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitCounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitCounter[]    findAll()
 * @method VisitCounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitCounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitCounter::class);
    }

    // /**
    //  * @return VisitCounter[] Returns an array of VisitCounter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VisitCounter
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
