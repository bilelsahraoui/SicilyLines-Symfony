<?php

namespace App\Repository;

use App\Entity\Traversee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Traversee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traversee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traversee[]    findAll()
 * @method Traversee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraverseeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traversee::class);
    }

    // /**
    //  * @return Traversee[] Returns an array of Traversee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Traversee
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
