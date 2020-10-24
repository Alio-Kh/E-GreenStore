<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface CommandeService
{
    public function save($request);
    // public function delete(Request $request);
}
