<?php

namespace App\Service\Impl;

use App\Entity\Produit;
use App\Entity\Promotion;
use App\Repository\ProduitRepository;
use App\Repository\PromotionRepository;
use App\Repository\TvaRepository;
use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Length;
use App\Service\ProduitService;

class PanierServiceImpl implements PanierService
{
    private $session;
    private $produitRepository;
    private $promotionRepository;
    private $tvaRepository;
    private $produitService;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepository, PromotionRepository $promotionRepository, TvaRepository $tvaRepository, ProduitService $produitService)
    {
        $this->session = $session;
        $this->produitRepository = $produitRepository;
        $this->promotionRepository = $promotionRepository;
        $this->tvaRepository = $tvaRepository;
        $this->produitService = $produitService;
    }
    public function addToPanier($id)
    {
        $panier = $this->session->get('panier', []);
        $p[] = [
            'panier' => $panier,
            'msg' => 2,
        ];
        $qte = $this->produitRepository->find($id)->getStock();

        if (!empty($panier[$id])) {
            if ($panier[$id] < (int)$qte) {
                $panier[$id]++;
                $msg = 1;
            } else {
                $msg = 0;
            }
        } elseif ((int)$qte > 1) {
            $panier[$id] = 1;
            $msg = 1;
        } else {
            $msg = 0;
        }
        $this->session->set('panier', $panier);
         $p[0]['panier'] = $panier;
        $p[0]['msg'] = $msg;
        $this->session->set('totalWithLivraison', $this->getTotal());
        return  $p;
    }

    public function removeFromPanier(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
        if($this->session->get('totalWithLivraison')){
            $livr=$this->session->get('totalWithLivraison')-$this->getTotal();
            $this->addToTotal($livr);
         }else{
           $this->session->set('totalWithLivraison', $this->getTotal());
         }
      }


    public function decreaseQte(int $id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id]))
            $panier[$id]--;
        else
            $panier[$id] = 1;

        $this->session->set('panier', $panier);

      if($this->session->get('totalWithLivraison')){
         $livr=$this->session->get('totalWithLivraison')-$this->getTotal();
         $this->addToTotal($livr);
      }else{
        $this->session->set('totalWithLivraison', $this->getTotal());
      }
        return $panier;
    }

    public function updatePanier()
    {
        $panier = $this->session->get('panier', []);
        $panier = null;
        $total =0;
        $this->session->set('totalItems', $total);
        $this->session->set('panier', $panier);
        $this->session->set('totalWithLivraison', $total);
    }

    public function getFullPanier(): array
    {

        $panier = $this->session->get('panier', []);

        $panierWithData = [];


        if (!empty($panier)) {
            foreach ($panier as $id => $qte) {

                $panierWithData[] = [
                    'produit' => $this->produitRepository->find($id),
                    'qte' => $qte,
                    'promotion' => $this->produitService->getPromotion($this->produitRepository->find($id)->getPromotion()->getId())

                ];
            }
        }

        return $panierWithData;
    }

    public function getNombreProduit(): int
    {
        $nombreProduit = 0;
        if (!empty($panier)) {
            foreach ($panier as $id => $qte) {
                $nombreProduit++;
            }
        }
        return $nombreProduit;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        $panierWithData = $this->getFullPanier();
        $reduction = 0.0;

        foreach ($panierWithData as $item) {
            $total += $item['produit']->getPrixUnitaire() * $item['qte'];
            $reduction = $this->produitService->getReduction($item['produit'], $item['produit']->getPromotion()->getId());
        }

        return  $total - $reduction;
    }
    
    public function getTotalWithLivraison():float{
        return $this->session->get('totalWithLivraison');
    }
    
    public function addToTotal($val) 
    {
      
        $this->session->set('totalWithLivraison', $this->session->get('totalWithLivraison')+$val);

    }

    public function decreaseFromTotal($val) 
    {
      
        $this->session->set('totalWithLivraison',  $this->session->get('totalWithLivraison')-$val);

    }
}
