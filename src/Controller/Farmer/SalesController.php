<?php

namespace App\Controller\Farmer;

use App\Entity\Agriculteur;
use App\Entity\Market;
use App\Repository\AgriculteurRepository;
use App\Repository\MarketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("agriculteur/ventes")
 */
class SalesController extends AbstractController
{

    /**
     * @Route("/", name="sales")
     */
    public function sales(MarketRepository $marketRepository, AgriculteurRepository $agriculteurRepository)
    {
        $agriculteur = new Agriculteur();
        $agriculteur = $agriculteurRepository->findOneBy(array('id' => $this->getUser()->getAgriculteur()->getId()));
        if ($agriculteur == null) {
            $markets = null;
        } else {
            $markets  = $agriculteur->getMarkets();
        }
        return $this->render('farmer/sales/sales.html.twig', [
            'controller_name' => 'ventes', 'markets' => $markets
        ]);
    }

    /**
     * @Route("/{id}/marche", name="sales_market")
     */
    public function salesMarket(Market $market)
    {

        $this->denyAccessUnlessGranted('SHOW', $market);
        return $this->render('farmer/sales/sales-market.html.twig', [
            'controller_name' => 'ventes', 'market' => $market
        ]);
    }
}
