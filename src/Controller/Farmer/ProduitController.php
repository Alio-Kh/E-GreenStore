<?php

namespace App\Controller\Farmer;

use App\Entity\Market;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use App\Repository\PromotionRepository;
use App\Service\ProduitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("marche/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/{id}/ajouter", name="add_product")
     */
    public function addProduct(Market $market, ProduitService $produitService, Request $request,  CategorieRepository $categorieRepository)
    {
        $this->denyAccessUnlessGranted('ADDP', $market);
        $categories = $categorieRepository->findAll();
        if ($request->request->has('addProduct')) {
            $result = $produitService->addP($market, $request);
            $produit = $result[1];
            if (!empty($result[2])) {
                $message = $result[2];
                return $this->render('farmer/produit/add-product.html.twig', [
                    'controller_name' => 'produit | ajouter', 'market' => $market, 'produit' => $produit, 'message' => $message, 'categories' => $categories
                ]);
            } else {
                return $this->render('farmer/produit/add-product.html.twig', [
                    'controller_name' => 'produit | ajouter', 'add' => 'Produit ajouté', 'market' => $market, 'categories' => $categories
                ]);
            }
        }
        return $this->render('farmer/produit/add-product.html.twig', [
            'controller_name' => 'produit | ajouter', 'categories' => $categories, 'market' => $market
        ]);
    }

    /**
     * @Route("/{id}/edite", name="edit_product")
     */
    public function editProduct(ProduitService $produitService, CategorieRepository $categorieRepository, Request $request, Produit $produit, PromotionRepository   $promotionRepository)
    {
        $this->denyAccessUnlessGranted('EDIT', $produit);
        $categories = $categorieRepository->findAll();
        $produit->setPromotion($promotionRepository->findOneBy(['id' => $produit->getPromotion()->getId()]));
        if ($request->request->has('editProduct')) {
            $result = $produitService->editP($request, $produit);
            $produit = $result[1];
            if (!empty($result[2])) {
                $message = $result[2];
                return $this->render('farmer/produit/edit-product.html.twig', [
                    'controller_name' => 'produit |edite', 'produit' => $produit, 'message' => $message, 'categories' => $categories
                ]);
            } else {
                return $this->render('farmer/produit/edit-product.html.twig', [
                    'controller_name' => 'produit |edite', 'produit' => $produit, 'editer' => 'Produit  edité', 'categories' => $categories
                ]);
            }
        } else {
            return $this->render('farmer/produit/edit-product.html.twig', [
                'controller_name' => 'produit |edite', 'produit' => $produit, 'categories' => $categories
            ]);
        }
    }



    /**
     * @Route("/{id}/details", name="details_product")
     */
    public function detailsProduct(ClientRepository $clientRepository, Produit $produit, CategorieRepository $categorieRepository, PromotionRepository $promotionRepository)
    {
        $this->denyAccessUnlessGranted('SHOW', $produit);

        $produit->setCategorie($categorieRepository->findOneBy(['id' => $produit->getCategorie()->getId()]));
        $produit->setPromotion($promotionRepository->findOneBy(['id' => $produit->getPromotion()->getId()]));
        foreach ($produit->getCommentaires() as $commentaire) {
            $commentaire->setClient($clientRepository->findOneBy(['id' => $commentaire->getClient()->getId()]));
        }
        return $this->render('farmer/produit/details-product.html.twig', [
            'controller_name' => 'produit | details  ', 'produit' => $produit
        ]);
    }



    /**
     * @Route("/{id}/delete", name="delete_product")
     */
    public function deleteProduct(Request $request, Produit $produit, ProduitRepository $produitRepository)
    {
        $this->denyAccessUnlessGranted('delete', $produit);
        if ($produitRepository->verifyForDelete($produit)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            // $entityManager->flush();
        } else {
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
