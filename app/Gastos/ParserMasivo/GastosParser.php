<?php

namespace App\Gastos\ParserMasivo;

use App\Gastos\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GastosParser
{
    protected $glosasTipoGasto = null;

    protected $datosMasivos = null;

    protected $cuentaAsociada = 0;

    public function procesaMasivo(Request $request): Collection
    {
        return collect([]);
    }

    public function getParserError()
    {
        return get_class($this) == NullParser::class
            ? ['ParserError' => 'No se puede ingresar masivo esta cuenta']
            : [];
    }

    public function getCuenta(): Cuenta
    {
        return Cuenta::findOrNew($this->cuentaAsociada);
    }

    public function hasCuenta(int $cuenta): bool
    {
        return $this->cuentaAsociada == $cuenta;
    }
}
