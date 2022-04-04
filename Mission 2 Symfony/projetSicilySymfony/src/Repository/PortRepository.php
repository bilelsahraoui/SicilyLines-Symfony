<?php

namespace App\Repository;

use App\Entity\Port;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Port|null find($id, $lockMode = null, $lockVersion = null)
 * @method Port|null findOneBy(array $criteria, array $orderBy = null)
 * @method Port[]    findAll()
 * @method Port[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Port::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Port $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Port $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function ShowPortDepart() : ?array
    {
        
        $req = "select p from App\Entity\Port p where p.id IN (select identity (p_depart.portDepart) from App\Entity\Liaison p_depart)";

        return $this->getEntityManager()->createQuery($req)->getResult();

    }

    public function ShowPortArrivee() : ?array
    {
        
        $req = "select p from App\Entity\Port p where p.id IN (select identity (p_arrivee.portArrivee) from App\Entity\Liaison p_arrivee)";

        return $this->getEntityManager()->createQuery($req)->getResult();

    }

    // /**
    //  * @return Port[] Returns an array of Port objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Port
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
