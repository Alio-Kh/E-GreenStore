<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\BlogRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, BlogRepository $blogRepository)
    {  
        $produitsBio= $produitRepository->findByBio();
        $bestBlogResult= $blogRepository->bestBlogs();
        $result= $produitRepository->findByRecommedation();
        $produitsMieuxNotes =array();
        foreach ( $result  as $p) {
            array_push($produitsMieuxNotes, $p[0]);
        }
        $bestBlogs= array();
        foreach ( $bestBlogResult  as $b) {
            array_push($bestBlogs, $b[0]);
        }

        $derniersProduits=$produitRepository->findByDate();
        $categories = $categorieRepository->findCategoryNotEmpty();
        $categoriesVente=array();
        foreach($categories as $c){
               $ca=new Categorie();
                  $ps=$produitRepository->findByVente($c);
                  if(!empty($ps)){
                    $ca->setLibelle($c->getLibelle());
                    foreach($ps  as $p){
                        $ca->addProduit($p[0]);
                    }
                     array_push($categoriesVente, $ca);
                  }
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Accuiel', 'produitsBio'=>$produitsBio,'categoriesVente'=>$categoriesVente,'produitsMieuxNotes'=>$produitsMieuxNotes,'categories' => $categories,'derniersProduits'=>$derniersProduits , 'bestBlogs'=>$bestBlogs
        ]);
    }
   
}
