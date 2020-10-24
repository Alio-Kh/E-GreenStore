<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\MarketRepository;
use App\Repository\ProduitRepository;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopDetailsController extends AbstractController
{
    /**
     * @Route("/{id}/details", name="shop_details")
     */
    public function index(PanierService $panier,CategorieRepository $categorieRepository,Produit $produit,MarketRepository  $marketRepository)
    {           $categories = $categorieRepository->findCategoryNotEmpty(); 
         $market=$marketRepository->findOneBy(['id'=>$produit->getMarket()->getId()]);
         $panierWithData = $panier->getFullPanier();
         $qteProduit=0;
         foreach($panierWithData as $p){
             if($p['produit']==$produit){
                $qteProduit=$p['qte']; 
                break;
             }
         }
          
         return $this->render('shop_details/index.html.twig', [
            'controller_name' => 'produit |details','qtePanier'=>$qteProduit, 'produit' => $produit,'market'=>$market,'categories'=>$categories
        ]);
    }
   
}
