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

    public function searchBy($filter, $searchKey, $creator)
    {
        return $this->createQueryBuilder('n')
            ->Where("n.".$filter." LIKE :searchKey")
            ->andWhere("n.creator  = :creator")
            ->setParameter('searchKey', "%".$searchKey."%")
            ->setParameter('creator', $creator)
            ->orderBy('n.updatedAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
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
