<?php

namespace App\Controller\Farmer;

use App\Entity\Agriculteur;
use App\Entity\Market;
use App\Repository\AgriculteurRepository;
use App\Repository\MarketRepository;
use App\Service\MarketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marches" )
 */
class MarketController extends AbstractController
{
    /**
     * @Route("/", name="markets")
     */
    public function markets(AgriculteurRepository $agriculteurRepository)
    {

        $agriculteur = new Agriculteur();
        $agriculteur =   $this->getUser()->getAgriculteur();
        if ($agriculteur == null) {
            $markets = null;
        } else {
            $markets  = $agriculteur->getMarkets();
        }
        return $this->render('farmer/market/markets.html.twig', [
            'controller_name' => 'marchés', 'markets' => $markets
        ]);

        /* $response->setMaxAge(12);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('no-cache', true); 
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;*/
    }

    /**
     * @Route("/marche/{id}", name="show_market")
     */
    public function showMarket(Market $market)
    {
        $this->denyAccessUnlessGranted('SHOW', $market);
        return $this->render('farmer/market/show-market.html.twig', [
            'controller_name' => 'marché', 'produits' => $market->getProduits(), 'market' => $market
        ]);
    }

    /**
     * @Route("/ajouter", name="add_market")
     */
    public function addMarket(Request $request, MarketService $marketService, MarketRepository $marketRepository)
    {
        $m = new Market();
        $this->denyAccessUnlessGranted('ADD', $m);
        if ($request->request->has('addMarket')) {
            $result = $marketService->addM($request);
            $market = $result[1];
            if (!empty($result[2])) {
                $message = $result[2];
                return $this->render('farmer/market/add-market.html.twig', [
                    'controller_name' => 'FarmerController',   'message' => $message, 'market' => $market
                ]);
            } else {

                $market = $marketRepository->findByMaxId();
                return $this->redirectToRoute('add_product', ['id' => $market[0]->getId()]);
            }
        }
        return $this->render('farmer/market/add-market.html.twig', [
            'controller_name' => 'marché | ajouter'
        ]);
    }
    
    /**
     * @Route("/marche/{id}/edite", name="edit_market")
     */
    public function editMarket(Market $market, Request $request, MarketService $marketService, MarketRepository $marketRepository)
    {
        $this->denyAccessUnlessGranted('EDIT', $market);
        if ($request->request->has('editMarket')) {
            $result = $marketService->editM($request, $market);
            $market = $result[1];
            if (!empty($result[2])) {
                $message = $result[2];
                return $this->render('farmer/market/edit-market.html.twig', [
                    'controller_name' => 'marché | editer', 'message' => $message, 'market' => $market
                ]);
            } else {

                return $this->render('farmer/market/edit-market.html.twig', [
                    'controller_name' => 'marché | editer', 'edit' => "Le marché editer", 'market' => $market
                ]);
            }
        }
        return $this->render('farmer/market/edit-market.html.twig', [
            'controller_name' => 'marché | editer', 'market' => $market
        ]);
    }
}
