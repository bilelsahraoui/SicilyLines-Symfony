<?php

namespace App\Repository;

use App\Entity\Tarifer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tarifer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tarifer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tarifer[]    findAll()
 * @method Tarifer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarifer::class);
    }

    // /**
    //  * @return Tarifer[] Returns an array of Tarifer objects
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
    public function findOneBySomeField($value): ?Tarifer
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


public function getTarifTraversee($idLiaison,$idPeriode){
    $req="select identity(t.Type), t.tarif from App\Entity\Tarifer t 
    where t.Periode =  ". $idPeriode ."  
    and t.Liaison =  ". $idLiaison . " ";
    
    return $this->getEntityManager()->createQuery($req)->getResult();
        }

}
