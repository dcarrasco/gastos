<?php

namespace App\Models\Gastos\ParserMasivo;

use Illuminate\Http\Request;
use App\Models\Gastos\Cuenta;
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

    public function agregarDatosMasivos(Request $request): bool
    {
        return $this->datosMasivos->count() == $this->datosMasivos
            ->filter->hasTipoGasto()
            ->count();
    }
}
