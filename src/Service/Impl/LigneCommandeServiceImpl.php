<?php

namespace App\Service\Impl;

use App\Service\LigneCommandeService;
use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Request;

class LigneCommandeServiceImpl implements LigneCommandeService
{
    private $agriculteurRepository;
    private $panierService;

    public function __construct(PanierService $panierService)
    {
        $this->panierService = $panierService;
    }


    
    public function save(Request $request)
    {
        
    }
}
