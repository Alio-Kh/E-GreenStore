<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FilterController extends AbstractController
{
    /**
     * @Route("/filter", name="filter")
     */
    public function index(Request $request, CategorieRepository $categorieRepository, PaginatorInterface $paginator)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $data = $request->query->all();
        $produits = new  ArrayCollection();
        $tmp = 1;
        foreach ($categories as $categorie) {
            if (array_key_exists(str_replace(' ', '_', $categorie->getLibelle()), $data)) {
                foreach ($categorie->getProduits() as $produit) {
                    $produits->add($produit);
                }
                $tmp = 0;
            }
        }
        if ($tmp) {
            foreach ($categories as $categorie) {
                foreach ($categorie->getProduits() as $produit) {
                    $produits->add($produit);
                }
            }
        }
        if (array_key_exists('prix', $data) && array_key_exists('bio', $data) && array_key_exists('promotion', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getPrixUnitaire() < 100 && $value->getIsBio() && $value->getPromotion()->getReduction() > 0 && $value->getPromotion()->getDateDebut() <= new \DateTime() && $value->getPromotion()->getDateFin() >= new \DateTime()) {
                    return true;
                } else {
                    return false;
                }
            });
        } elseif (array_key_exists('prix', $data) && array_key_exists('bio', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getPrixUnitaire() < 100 && $value->getIsBio()) {
                    return true;
                } else {
                    return false;
                }
            });
        } elseif (array_key_exists('prix', $data) && array_key_exists('promotion', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getPrixUnitaire() < 100  && $value->getPromotion()->getReduction() > 0 && $value->getPromotion()->getDateDebut() <= new \DateTime() && $value->getPromotion()->getDateFin() >= new \DateTime()) {
                    return true;
                } else {
                    return false;
                }
            });
        } elseif (array_key_exists('bio', $data) && array_key_exists('promotion', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getIsBio() && $value->getPromotion()->getReduction() > 0 && $value->getPromotion()->getDateDebut() <= new \DateTime() && $value->getPromotion()->getDateFin() >= new \DateTime()) {
                    return true;
                } else {
                    return false;
                }
            });
        } elseif (array_key_exists('prix', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getPrixUnitaire() < 100) {
                    return true;
                } else {
                    return false;
                }
            });
        } elseif (array_key_exists('bio', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getIsBio()) {
                    return true;
                } else {
                    return false;
                }
            });
        } elseif (array_key_exists('promotion', $data)) {
            $produits = $produits->filter(function ($value, $key) {
                if ($value->getPromotion()->getReduction() > 0 && $value->getPromotion()->getDateDebut() <= new \DateTime() && $value->getPromotion()->getDateFin() >= new \DateTime()) {
                    return true;
                } else {
                    return false;
                }
            });
        }

         $request->getSession()->set('filter',$data);

         $produits = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            10
        );
         return $this->render('filter/index.html.twig', [
            'controller_name' => 'Filter ', 'categories' => $categories,'produits'=>$produits
        ]);
    }
}
