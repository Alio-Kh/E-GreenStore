<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\TypeLivraisonRepository;
use App\Service\PanierService;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
