<?php

namespace App\Controller\Farmer;

use App\Entity\Produit;
use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agriculteur/statistiques" )
 */
class statisticsController extends AbstractController
{
    /**
     * @Route("/", name="farmer_statistics")
     */
    public function index()
    {
        return $this->render('farmer/statistics/index.html.twig', [
            'controller_name' => 'statistiques',
        ]);
    }
    /**
     * @Route("/produits", name="products_statistics")
     */
    public function products()
    {
        return $this->render('farmer/statistics/products.html.twig', [
            'controller_name' => 'statistiques',
            'markets'=>$this->getUser()->getAgriculteur()->getMarkets()
            ]);
    }


    /**
     * @Route("/ans", name="statistics_years")
     */
    public function charts(VenteRepository $venteRepository)
    {
        $user = $this->getUser();
        $agriculteur = $user->getAgriculteur();
        $ventesTotal = $venteRepository->findSalesYearsByFarmer($agriculteur);
        $ventes = array();
        $years = array();
        if (!empty($ventesTotal)) {
            $year = $ventesTotal[0]['year'];
            $tmp = array();
            array_push($years, $year);
            $j = 1;
            for ($i = 0; $i < count($ventesTotal); $i++) {
                if ($ventesTotal[$i]['year'] == $year) {
                    array_push($tmp, $ventesTotal[$i]);
                    $j = 1;
                } else {
                    array_push($years, $ventesTotal[$i]['year']);
                    $j = 0;
                    $year = $ventesTotal[$i]['year'];
                    array_push($ventes, $tmp);
                    $tmp = array();
                    array_push($tmp, $ventesTotal[$i]);
                    if (count($ventesTotal) - 1 == $i) {
                        $j = 1;
                    }
                }
            }
            if ($j) {
                array_push($ventes, $tmp);
            }
        }
        return $this->render('farmer/statistics/charts-years.html.twig', [
            'controller_name' => 'statistiques', 'ventes' => $ventes, 'years' => $years
        ]);
    }
     /**
     * @Route("/produit/{id}", name="product_statistics")
     */
    public function chartProduct(Produit $produit,VenteRepository $venteRepository)
    {  
        $this->denyAccessUnlessGranted('SHOW', $produit);
        $ventesTotal = $venteRepository->findSalesYearsByProduct($produit);
        $ventes = array();
        $years = array();
        if (!empty($ventesTotal)) {
            $year = $ventesTotal[0]['year'];
            $tmp = array();
            array_push($years, $year);
            $j = 1;
            for ($i = 0; $i < count($ventesTotal); $i++) {
                if ($ventesTotal[$i]['year'] == $year) {
                    array_push($tmp, $ventesTotal[$i]);
                    $j = 1;
                } else {
                    array_push($years, $ventesTotal[$i]['year']);
                    $j = 0;
                    $year = $ventesTotal[$i]['year'];
                    array_push($ventes, $tmp);
                    $tmp = array();
                    array_push($tmp, $ventesTotal[$i]);
                    if (count($ventesTotal) - 1 == $i) {
                        $j = 1;
                    }
                }
            }
            if ($j) {
                array_push($ventes, $tmp);
            }
        }
        return $this->render('farmer/statistics/charts-product.html.twig', [
            'controller_name' => 'statistiques', 'ventes' => $ventes, 'years' => $years,
            'produit'=>$produit
        ]);
    }
}
