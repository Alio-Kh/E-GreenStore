<?php

namespace App\Service;

use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;

interface FactureService
{
    public function save(Commande $commande, $data);
    // public function delete(Request $request);
}
