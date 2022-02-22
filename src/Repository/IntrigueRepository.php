<?php

namespace App\Repository;

use App\Entity\Intrigue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Intrigue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intrigue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intrigue[]    findAll()
 * @method Intrigue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntrigueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intrigue::class);
    }

    // /**
    //  * @return Intrigue[] Returns an array of Intrigue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Intrigue
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
