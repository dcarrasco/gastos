<?php

namespace App\Gastos\ParserMasivo;

use Illuminate\Http\Request;

interface GastosParser
{
    public function procesaMasivo(Request $request);
}
