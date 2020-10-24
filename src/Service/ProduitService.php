<?php
namespace App\Service;

use App\Entity\Market;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;

interface ProduitService {
    public function validate(Request $request, Produit $p);
    public function  editP(Request $request, Produit $produit);
    public function  addP(Market  $market, Request $request);
    public function getPromotion(int $idPromotion);
    public function getReduction(Produit $produit, int $idPromotion);
 }