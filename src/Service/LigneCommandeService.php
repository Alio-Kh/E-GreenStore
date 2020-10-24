<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface LigneCommandeService
{
    public function save(Request $request);
    // public function delete(Request $request);
}
