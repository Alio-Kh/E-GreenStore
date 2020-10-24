<?php

namespace App\Service;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;

interface PaymentService
{
    // public function save(Request $request);
    // public function delete(Request $request);
    public function getUrlAccessClient($amount);
}
