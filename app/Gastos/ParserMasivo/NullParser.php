<?php

namespace App\Gastos\ParserMasivo;

use Illuminate\Http\Request;

class NullParser implements GastosParser
{
    protected $glosasTipoGasto = null;

    protected $datosMasivos = null;


    public function procesaMasivo(Request $request)
    {
        return collect([]);
    }
}
