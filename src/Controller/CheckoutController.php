<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\TypeLivraisonRepository;
use App\Service\PanierService;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(PaymentService $paymentService,PanierService $panier, AuthenticationUtils $authenticationUtils, CategorieRepository $categorieRepository, TypeLivraisonRepository $typeLivraisonRepository)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();

        $panierWithData = $panier->getFullPanier();

        $total = $panier->getTotal();

        $totalTtc = $total * 1.2;

        $error = $authenticationUtils->getLastAuthenticationError();

        $typeLivraisons = $typeLivraisonRepository->findAll();

        $urlAccessClient = $paymentService->getUrlAccessClient($totalTtc * 100);

        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'Checkout ',
            'panier' => $panierWithData, "total" => $total, 'totalTtc' => $totalTtc,
            'error' => $error, 'categories' => $categories, 'typeLivraisons' =>  $typeLivraisons,
            "urlAccessClient" => $urlAccessClient
        ]);
    }
     /**
     * @Route("/livr_checked", name="livr_checked")
     */
    public function Livrchecked(Request $request,PaymentService $paymentService,PanierService $panier,  TypeLivraisonRepository $typeLivraisonRepository)
    {
        $id=trim($request->get('val'));
        $frais=0;
        if($request->getSession()->has('typeLivraison')){
            $frais=$typeLivraisonRepository->findOneBy(['id'=>$request->getSession()->get('typeLivraison')])->getFrais();
            $panier->decreaseFromTotal($frais);
            $frais=$typeLivraisonRepository->findOneBy(['id'=>$id])->getFrais();
            $panier->addToTotal($frais);

        }else{
            $frais=$typeLivraisonRepository->findOneBy(['id'=>$id])->getFrais();
            $panier->addToTotal($frais);

        }
        
        $request->getSession()->set('typeLivraison',$id);
        $total=$panier->getTotalWithLivraison();

        return $this->json(['code' => 200, 
                            'total'=> $total,
                            'frais'=>$frais
                        ], 200);

    }
}
