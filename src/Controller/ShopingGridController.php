<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;

class ShopingGridController extends AbstractController
{
    /**
     * @Route("/shoping/grid", name="shoping_grid", methods={"GET","POST"})
     */
    public function index(Request $request, ProduitRepository $produitRepository, PaginatorInterface $paginator, CategorieRepository $categorieRepository)
    {
        $categories = $categorieRepository->findCategoryNotEmpty(); 
        $listProduit = $produitRepository->findProduitNotSale();
        if ($request->request->has('trier') || $request->getSession()->get('trier')) {
            if ($request->request->has('trier')) {
                $trier = $request->request->get('trier');
                $request->getSession()->set("trier", $trier);
                if ($trier == 1) {
                    usort($listProduit, fn ($a, $b) => $a->getPrixUnitaire() - $b->getPrixUnitaire());
                } elseif ($trier == 2) { 
                    usort($listProduit, fn ($a, $b) => $a->getPrixUnitaire() < $b->getPrixUnitaire()? +1 : -1);
                 } 
            } else {
                if ($request->getSession()->get('trier') == 1) {
                    usort($listProduit, fn ($a, $b) => $a->getPrixUnitaire() - $b->getPrixUnitaire());
                }elseif ($request->getSession()->get('trier') == 2) { 
                    usort($listProduit, fn ($a, $b) => $a->getPrixUnitaire() < $b->getPrixUnitaire()? +1 : -1);
                }
            }
        }
        $listProduit = $paginator->paginate(
            $listProduit,
            $request->query->getInt('page', 1),
            10
        );
        $produitsPromotion = $produitRepository->findByReduction();
        
        $produits = $produitRepository->findAll();
        $result = $produitRepository->findByRecommedation();
        $produitsMieuxNotes = array();
        foreach ($result  as $p) {
            array_push($produitsMieuxNotes, $p[0]);
        }
        $derniersProduits = $produitRepository->findByDate();

        return $this->render('shoping_grid/index.html.twig', [
            'controller_name' => 'achat', 'categories'=>$categories,'listProduit' => $listProduit, 'produitsPromotion' => $produitsPromotion, 'produitsMieuxNotes' => $produitsMieuxNotes, 'produits' => $produits, 'derniersProduits' => $derniersProduits
        ]);
    }
}
