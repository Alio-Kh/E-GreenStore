<?php

namespace App\Controller;

use App\Entity\Market;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsMarketController extends AbstractController
{
    /**
     * @Route("{id}/produits", name="products_market")
     */
    public function index(Market $market,CategorieRepository $categorieRepository
    )
    {   
        $categories = $categorieRepository->findCategoryNotEmpty();
        
        $recommedations=0;
        foreach($market->getProduits() as $p){
            $recommedations=$recommedations+count($p->getRecommedations());
        }
        return $this->render('products_market/index.html.twig', [
            'controller_name' => 'produits','market'=>$market ,'recommedations'=>$recommedations,'categories'=>$categories
        ]);
    }
}
