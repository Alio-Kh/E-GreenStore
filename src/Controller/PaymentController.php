<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Service\CommandeService;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(Request $request, CategorieRepository $categorieRepository, ProduitRepository $produitRepository)
    {
        // \Stripe\Stripe::setApiKey('sk_test_51H5ZPgFR4NVul0MEg6HYCinxV0epUEpu64ZKrERj5vRctw4zI25B3v0523lTkWECqkQog3ToM0XsEkmKjkxIO5hG00Hj5Hi35Y');

        // $intent = \Stripe\PaymentIntent::create([
        //     'amount' => 1099,
        //     'currency' => 'usd',
        //     // Verify your integration in this guide by including this parameter
        //     'metadata' => ['integration_check' => 'accept_a_payment'],
        // ]);
        $categories = $categorieRepository->findCategoryNotEmpty();
        $categoriesVente = array();
        foreach ($categories as $c) {
            $ca = new Categorie();
            $ps = $produitRepository->findByVente($c);
            if (!empty($ps)) {
                $ca->setLibelle($c->getLibelle());
                foreach ($ps  as $p) {
                    $ca->addProduit($p[0]);
                }
                array_push($categoriesVente, $ca);
            }
        }

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController', 'categories' => $categories,
        ]);
    }

    /**
     * @Route("/payment/commnade/ajouter", name="add_commande")
     */
    public function saveCommande(Request $request, CommandeService $commandeService, CommandeRepository $commandeRepository, CategorieRepository $categorieRepository, ProduitRepository $produitRepository)
    {
        $commande = new Commande();

        $result = $commandeService->save($request);
        // $commande = $result[1];
        // dd($result);
        // if (!empty($result[2])) {
        //     $message = $result[2];
        //     return $this->render('farmer/market/add-market.html.twig', [
        //         'controller_name' => 'FarmerController',   'message' => $message, 'commande' => $commande,
        //     ]);
        // } else {

        //     $market = $commandeRepository->findByMaxId();
        // }

        $categories = $categorieRepository->findCategoryNotEmpty();
        $categoriesVente = array();
        foreach ($categories as $c) {
            $ca = new Categorie();
            $ps = $produitRepository->findByVente($c);
            if (!empty($ps)) {
                $ca->setLibelle($c->getLibelle());
                foreach ($ps  as $p) {
                    $ca->addProduit($p[0]);
                }
                array_push($categoriesVente, $ca);
            }
        }
        return $this->redirectToRoute('thank_you', ['categories' => $categories]);


        // return $this->render('thank_you/index.html.twig', [
        //     'controller_name' => 'ThankYouController', 'categories' => $categories,
        // ]);
    }
}
