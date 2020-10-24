<?php

namespace App\Repository;

use App\Entity\Agriculteur;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
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
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     
  
     
    /**
     *  @return Produit[] Returns an array of Produit objects
     */
    public function findByDate()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.dateAjout', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByRecommedation()
    {   
        return  $this->createQueryBuilder('p ' )
        ->join('App\Entity\RecommendationProduit ','r')
        ->where('p.id = r.produit')
        ->groupBy('p.id')
        ->select('p ,COUNT(p.id) as nbrRecommedation')
        ->orderBy('nbrRecommedation', 'DESC')
        ->setMaxResults(6)
        ->getQuery()
        ->getResult();
    }
    
    
    public function findByVente($categorie)
    {   
        return  $this->createQueryBuilder('p ' )
        ->join('App\Entity\Vente ','v')
        ->where('p.id = v.produit and p.categorie=:categorie')
        ->setParameter('categorie', $categorie)
        ->groupBy('p.id')
        ->select('p ,COUNT(p.id) as nbrVente')
        ->orderBy('nbrVente', 'DESC')
        ->setMaxResults(6)
        ->getQuery()
        ->getResult();
    }
    public function findByBio()
    {      

        return  $this->createQueryBuilder('p ' )
         ->where('p.isBio=1')
         ->setMaxResults(6)
         ->getQuery()
        ->getResult();
    }
    public function findByReduction()
    {     $date =new \DateTime();
        
        return  $this->createQueryBuilder('p ' )
        ->join('App\Entity\Promotion ','r')
        ->where(' p.promotion=r.id and  r.reduction>0 and  r.dateFin>=:date and r.dateDebut<=:date')
        ->setParameter('date',$date)
        ->getQuery()
        ->getResult();
    }
    public function findProduitNotSale()
    {     $date =new \DateTime();
        return  $this->createQueryBuilder('p ' )
        ->join('App\Entity\Promotion ','r')
        ->where(' p.promotion=r.id and  r.dateFin<:date')
        ->orWhere(' p.promotion=r.id and r.reduction=0')
        ->setParameter('date',$date)
        ->getQuery()
        ->getResult();
    }

    public function findByLibelle($val)
    {     
        return  $this->createQueryBuilder('p ' )
        ->where(' p.libelle like :val')
        ->setParameter('val','%'.$val.'%')
        ->getQuery()
        ->getResult();
    }

    public function findProductsNotSaleByFarmer(Agriculteur $agriculteur )
    {
           return $this->createQueryBuilder('p')
             ->join('App\Entity\Market','m')
             ->leftJoin('App\Entity\vente','v','WITH','v.produit=p.id')
             ->where(' p.market=m.id and m.agriculteur=:id  and v.produit is null')
             ->setParameter('id',$agriculteur->getId())
             ->getQuery()
             ->getResult()
        ; 
      
    }

    /* for delete the product should'n  be in any commande not delivery  */
    public function verifyForDelete(Produit $produit )
    {            

           $produit= $this->createQueryBuilder('p')
             ->join('App\Entity\LigneCommande','lc')
             ->join('App\Entity\Commande','c')
             ->join('App\Entity\Livraison','l')
             ->where(' p.id=lc.produit and lc.commande=c.id  and c.id=l.commande and p.id=:id and l.livree=true')
             ->setParameter('id',$produit->getId())
             ->getQuery()
             ->getResult()
        ; 
      return !empty($produit);
    }
}
