<?php

namespace App\Service;

interface PanierService
{
    public function addToPanier(int $id);

    public function removeFromPanier(int $id);

    public function decreaseQte(int $id);

    public function updatePanier();

    public function getFullPanier(): array;

    public function getNombreProduit(): int;

    public function getTotal(): float;

    public function addToTotal($val);

    public function decreaseFromTotal($val);
    
    public function getTotalWithLivraison():float;
}
