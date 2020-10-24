<?php

namespace App\Service;

use App\Entity\Market;
use Symfony\Component\HttpFoundation\Request;

interface MarketService
{
    public function addM(Request $request);
    public function editM(Request $request,Market $market);


}