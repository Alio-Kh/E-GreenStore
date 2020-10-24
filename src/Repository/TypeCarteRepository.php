<?php

namespace App\Repository;

use App\Entity\TypeCarte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeCarte|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCarte|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCarte[]    findAll()
 * @method TypeCarte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCarteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCarte::class);
    }

    // /**
    //  * @return TypeCarte[] Returns an array of TypeCarte objects
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
    public function findOneBySomeField($value): ?TypeCarte
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
