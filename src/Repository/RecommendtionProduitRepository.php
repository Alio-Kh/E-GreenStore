<?php

namespace App\Repository;

use App\Entity\RecommendtionProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecommendtionProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecommendtionProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecommendtionProduit[]    findAll()
 * @method RecommendtionProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendtionProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecommendtionProduit::class);
    }

    // /**
    //  * @return RecommendtionProduit[] Returns an array of RecommendtionProduit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecommendtionProduit
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
