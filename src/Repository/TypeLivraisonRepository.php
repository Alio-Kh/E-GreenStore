<?php

namespace App\Repository;

use App\Entity\TypeLivraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeLivraison[]    findAll()
 * @method TypeLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeLivraison::class);
    }

    // /**
    //  * @return TypeLivraison[] Returns an array of TypeLivraison objects
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
    public function findOneBySomeField($value): ?TypeLivraison
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
