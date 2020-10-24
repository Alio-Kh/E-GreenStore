<?php

namespace App\Repository;

use App\Entity\Agriculteur;
use App\Entity\Produit;
use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vente[]    findAll()
 * @method Vente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vente::class);
    }

    // /**
    //  * @return Vente[] Returns an array of Vente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vente
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findSalesYearsByFarmer(Agriculteur $agriculteur )
    {
           return $this->createQueryBuilder('v')
             ->select('v,SUM(v.prixVente) as ventes , SUBSTRING(v.dateVente, 1, 4) as year , SUBSTRING(v.dateVente, 6, 2) as month ')
             ->join('App\Entity\Produit','p')
             ->join('App\Entity\Market','m')
             ->where('v.produit=p.id and p.market=m.id and m.agriculteur=:id')
             ->setParameter('id',$agriculteur->getId())
             ->groupBy('year','month')
             ->orderBy('year , month','ASC')
             ->getQuery()
             ->getResult()
        ; 
      
    }
    public function findSalesYearsByProduct(Produit $produit )
    {
           return $this->createQueryBuilder('v')
             ->select('v,SUM(v.prixVente) as ventes , SUBSTRING(v.dateVente, 1, 4) as year , SUBSTRING(v.dateVente, 6, 2) as month ')
             ->join('App\Entity\Produit','p')
             ->where('v.produit=p.id and p.id=:id  ')
             ->setParameter('id',$produit->getId())
             ->groupBy('year','month')
             ->orderBy('year , month','ASC')
             ->getQuery()
             ->getResult()
        ; 
      
    }
    /**
     *  
     *
     * @param Agriculteur $agriculteur
     * @return String
     */
    public function ca(Agriculteur $agriculteur )
    {
           return $this->createQueryBuilder('v')
             ->select('SUM(v.prixVente) as ca  ')
             ->join('App\Entity\Produit','p')
             ->join('App\Entity\Market','m')
             ->where('v.produit=p.id and p.market=m.id and m.agriculteur=:id')
             ->setParameter('id',$agriculteur->getId())
             ->getQuery()
             ->getResult()
        ; 
      
    }
}
