<?php

namespace App\Repository;

use App\Entity\Liaison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Liaison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liaison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liaison[]    findAll()
 * @method Liaison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liaison::class);
    }

    /*
    public function findPortsDepart(){
        return $this->createQueryBuilder('l')
            ->andWhere('l.port_depart_id is not null')
            ->getQuery()
            ->getResult()
        ;
    }*/

    // /**
    //  * @return Liaison[] Returns an array of Liaison objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Liaison
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
