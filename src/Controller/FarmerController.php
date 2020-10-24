<?php

namespace App\Controller;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
 
/**
 * @Route("/agriculteur" )
 */
class FarmerController extends AbstractController
{
    /**
     * @Route("/", name="farmer")
     */
    public function index(VenteRepository $venteRepository, ProduitRepository $prCategorieRepository, CategorieRepository $categorieRepository)
    {
        $agriculteur = $this->getUser()->getAgriculteur();
        $produitsNonVendu = $prCategorieRepository->findProductsNotSaleByFarmer($agriculteur);
        $ventesAujourdhui = $categorieRepository->ventesAujourdhuiParCaregorie();
        $totaleVentes = 0;
        foreach ($ventesAujourdhui as $v) {
            $totaleVentes = $totaleVentes + (int)($v['nbrVentes']);
        }
        $ca = $venteRepository->ca($agriculteur);
        $nbrC = 0;
        $nbrJ = 0;
        foreach ($agriculteur->getMarkets() as $m) {
            foreach ($m->getProduits() as $p) {
                $nbrC = $nbrC + count($p->getCommentaires());
                $nbrJ = $nbrJ + count($p->getRecommedations());
            }
        }
        $ville = $agriculteur->getCommune()->getVille();
        return $this->render('farmer/index.html.twig', [
            'totaleVentes' => $totaleVentes,
            'ventesAujourdhui' => $ventesAujourdhui,
            'produitsNonVendu' => $produitsNonVendu,
            'ca' => $ca,
            'nbrC' => $nbrC,
            'nbrJ' => $nbrJ,
            'ville' => $ville
        ]);
    }

    
}
