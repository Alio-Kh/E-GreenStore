<?php

namespace App\Repository;

use App\Entity\RecommendationProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecommendationProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecommendationProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecommendationProduit[]    findAll()
 * @method RecommendationProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommendationProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecommendationProduit::class);
    }

    // /**
    //  * @return RecommendationProduit[] Returns an array of RecommendationProduit objects
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
    public function findOneBySomeField($value): ?RecommendationProduit
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
