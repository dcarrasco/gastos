<?php

namespace App\Gastos;

use Illuminate\Http\Request;

interface GastosParser
{
    public function procesaMasivo(Request $request);
}
