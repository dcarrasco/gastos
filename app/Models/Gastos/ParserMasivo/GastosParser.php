<?php

namespace App\Models\Gastos\ParserMasivo;

use App\Models\Gastos\Gasto;
use Illuminate\Http\Request;
use App\Models\Gastos\Cuenta;
use Illuminate\Support\Carbon;
use App\Models\Gastos\TipoGasto;
use Illuminate\Support\Collection;
use App\Models\Gastos\GlosaTipoGasto;

class GastosParser
{
    protected $descripcion = '';

    protected $glosasTipoGasto = null;

    protected $datosMasivos = null;

    protected $cuentaAsociada = 0;

    protected $separadorCampos = ' ';

    public function procesaMasivo(Request $request): Collection
    {
        if (is_null($request->cuenta_id)) {
            return collect([]);
        }

        $this->glosasTipoGasto = GlosaTipoGasto::getCuenta($request->cuenta_id);

        $this->datosMasivos = $this->requestDatosMasivos($request)
            ->filtrarLineasValidas($request)
            ->procesaLineas($request)
            ->filtraLineasExistentes($request)
            ->getDatosMasivos();

        return $this->getDatosMasivos();
    }

    protected function requestDatosMasivos(Request $request): GastosParser
    {
        $this->datosMasivos = collect(explode(PHP_EOL, $request->datos));

        return $this;
    }

    protected function filtrarLineasValidas(Request $request): GastosParser
    {
        return $this;
    }

    protected function filtraLineasExistentes(Request $request): GastosParser
    {
        $camposFiltro = ['cuenta_id', 'anno', 'fecha', 'serie', 'monto'];

        $this->datosMasivos = $this->datosMasivos
            ->filter(fn($gasto) => Gasto::where($gasto->only($camposFiltro))->get()->count() == 0);

        return $this;
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
        if (is_null($this->datosMasivos)) {
            return false;
        }

        return $this->datosMasivos->count() == $this->datosMasivos
            ->filter->hasTipoGasto()
            ->count();
    }

    public function getDatosMasivos()
    {
        return $this->datosMasivos;
    }

    public function __toString(): string
    {
        return $this->descripcion;
    }

    protected function procesaLineas(Request $request): GastosParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->map(fn($linea) => $this->procesaLineaMasivo($request, $linea));

        return $this;
    }

    protected function procesaLineaMasivo(Request $request, string $linea): Gasto
    {
        $linea = collect(explode($this->separadorCampos, $linea));
        $tipoGasto = $this->getTipoGasto($request, $linea);

        return new Gasto([
            'cuenta_id' => $request->cuenta_id,
            'anno' => $request->anno,
            'mes' => $request->mes,
            'fecha' => $this->getFecha($linea),
            'serie' => $this->getSerie($linea),
            'glosa' => $this->getGlosa($linea),
            'tipo_gasto_id' => $tipoGasto->id,
            'tipo_movimiento_id' => optional($tipoGasto->tipoMovimiento)->id,
            'monto' => $this->getMonto($linea),
            'usuario_id' => auth()->id(),
        ]);
    }

    protected function getTipoGasto(Request $request, Collection $linea): TipoGasto
    {
        $glosa = strtoupper($this->getGlosa($linea));

        $glosaTipoGasto = $this->glosasTipoGasto
            ->first(fn($glosaTipoGasto) => strpos($glosa, strtoupper($glosaTipoGasto->glosa)) !== false)
            ?? new GlosaTipoGasto();

        return $glosaTipoGasto->tipoGasto ?? new TipoGasto();
    }

    protected function getFecha(Collection $linea): Carbon
    {
        return new Carbon();
    }

    protected function getSerie(Collection $linea): string
    {
        return '';
    }

    protected function getGlosa(Collection $linea): string
    {
        return '';
    }

    protected function getMonto(Collection $linea): int
    {
        return 0;
    }
}
