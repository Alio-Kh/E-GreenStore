<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Service\PanierService;
use Symfony\Component\Routing\Annotation\Route;

class ShopingCartController extends AbstractController
{
    /**
     * @Route("/shoping/cart", name="shoping_cart")
     */
    public function index(PanierService $panier,CategorieRepository $categorieRepository)

    {
         $categories=$categorieRepository->findCategoryNotEmpty();
        $panierWithData = $panier->getFullPanier();

        $total = $panier->getTotal();

        $tva = $total * 0.2;

        $totalTtc = $tva + $total;

        return $this->render('shoping_cart/index.html.twig', [
            'controller_name' => ' Cart ', 'panier' => $panierWithData,
            "total" => $total, 'tva' => $tva, 'totalTtc' => $totalTtc,'categories'=>$categories
        ]);
        
    }

    /**
     * @Route("/shoping/cart/add?id={id}", name="cart_add")
     */
    public function addProduit($id, PanierService $panierService)
    {

        $panier = $panierService->addToPanier($id);
        if ($panier[0]['msg'] == 0) {
           return $this->json([
            'code' => 200,
            'message' => 'Qte Max',
            'l' => count($panier[0]['panier']),
            'max' => $panier[0]['msg'],
            'idProduit'=>$id,
            'qte'=>$panier[0]['panier'][$id]
         ], 200);
        }else {
           return $this->json([
            'code' => 200,
            'message' => 'produit bien ajouter ',
            'l' => count($panier[0]['panier']),
            'idProduit'=>$id,
            'qte'=>$panier[0]['panier'][$id]
         ], 200);
        }
        
        // return $this->redirectToRoute("shoping_grid");

    }

    /**
     * @Route("/shoping/cart/remove?id={id}", name="cart_remove")
     */
    public function remove($id, PanierService $panier)
    {
         $panier->removeFromPanier($id);
        $total = $panier->getTotal();
        $tva = $total * 0.2;
        $totalTtc = $tva + $total;
        return $this->json([
            'totalTtc'=>$totalTtc,
             'tva'=> $tva,
            'total' =>  $total,
            'idProduit'=>$id,
        ], 200);
        //return $this->redirectToRoute("shoping_cart");
    }

    /**
     * @Route("/shoping/cart/{id}/increase_qte", name="increase_qte")
     */
    public function addQte($id, PanierService $panierService)
    {
        $panier = $panierService->addToPanier($id);
        $qte = $panier[0]['panier'][$id];
        $total = $panierService->getTotal();
        $tva = $total * 0.2;
        $totalTtc = $tva + $total;
         return $this->json([
             'totalTtc'=>$totalTtc,
              'tva'=> $tva,
             'total' =>  $total,
             'qte' => $qte,
             'idProduit'=>$id,
             'msg' => $panier[0]['msg'],
         ], 200);
       // return $this->redirectToRoute("shoping_cart");
    }

    /**
     * @Route("/shoping/cart/{id}/decrease_qte", name="decrease_qte")
     */
    public function decreaseQte($id, PanierService $panierService)
    {
       $panier = $panierService->decreaseQte($id);
       $qte= $panier[$id];
       $total = $panierService->getTotal();
       $tva = $total * 0.2;
       $totalTtc = $tva + $total;
        return $this->json([
            'totalTtc'=>$totalTtc,
             'tva'=> $tva,
            'total' =>  $total,
            'qte' => $qte,
            'idProduit'=>$id,
        ], 200);
        //  return $this->redirectToRoute("shoping_cart");
    }

    /**
     * @Route("/shoping/cart/update_panier", name="update_panier")
     */
    public function updatePanier(PanierService $panierService)
    {
        $panierService->updatePanier();
        return $this->redirectToRoute("shoping_cart");
    }

    // /**
    //  * @Route("/shoping/cart/{id}/add_qte", name="qte_add")
    //  */
    // public function like($id, Request $request, SessionInterface $session)
    // {
    //     $panier = $session->get('panier', []);

    //     if ($request->isXmlHttpRequest()) {
    //         $var =  json_decode($request->request->get('$data'), true);

    //         $qte = $var['qte'];

    //         var_dump('ok' + $qte);
    //         dd($qte);
    //         $panier[$id]->qte = $qte;

    //         $session->set('panier', $panier);
    //     }
    // }
}
