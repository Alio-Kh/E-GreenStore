<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    // /**
    //  * @return Categorie[] Returns an array of Categorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categorie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function ventesAujourdhuiParCaregorie()
    {
        $date = new \DateTime();
        return $this->createQueryBuilder('c')
            ->select('c ,count(c) as nbrVentes')
            ->join('App\Entity\Vente', 'v')
            ->join('App\Entity\Produit', 'p')
            ->Where('c.id =p.categorie and p.id=v.produit and SUBSTRING(v.dateVente, 1, 4)=SUBSTRING(:val, 1, 4) and SUBSTRING(:val, 6, 2)=SUBSTRING(v.dateVente, 6, 2)  and SUBSTRING(:val, 9, 2)= SUBSTRING(v.dateVente, 9, 2)')
            ->setParameter('val', $date)
            ->groupBy('c.id')
            ->orderBy('nbrVentes', 'desc')
            ->getQuery()
            ->getResult();
    }

    public function findBlogByPublished()
    {
        $categories = $this->createQueryBuilder('c')
            ->join('App\Entity\Blog', 'b')
            ->Where('c.id =b.categorie and b.published=1')
            ->getQuery()
            ->getResult();
 
        return $categories;
    }

    public function findCategoryNotEmpty()
    {
        $categorys=new ArrayCollection();
       $categorys = $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
       
       $categorys= array_filter($categorys, function($category, $k) {
            return count($category->getProduits())>0;
        }, ARRAY_FILTER_USE_BOTH);
         return $categorys;
    }
}
