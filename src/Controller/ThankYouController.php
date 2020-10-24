<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ThankYouController extends AbstractController
{
    /**
     * @Route("/thank/you", name="thank_you")
     */
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository)
    {
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
        return $this->render('thank_you/index.html.twig', [
            'controller_name' => 'ThankYouController', 'categories' => $categories,
        ]);
    }
}
