<?php

namespace App\Models\Gastos\ParserMasivo;

use App\Models\Gastos\Cuenta;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\GlosaTipoGasto;
use App\Models\Gastos\TipoGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

abstract class GastosParser
{
    protected string $descripcion = '';

    /** @var Collection<array-key, GlosaTipoGasto> */
    protected $glosasTipoGasto;

    /** @var Collection<array-key, string> */
    protected $datosMasivos;

    /** @var Collection<array-key, Gasto> */
    protected $datosMasivosProcesados;

    protected int $cuentaAsociada = 0;

    /** @var non-empty-string */
    protected string $separadorCampos = ' ';

    /** @return Collection<array-key, Gasto>  */
    public function procesaMasivo(Request $request): Collection
    {
        if (is_null($request->input('cuenta_id'))) {
            return collect();
        }

        $this->glosasTipoGasto = GlosaTipoGasto::getCuenta($request->input('cuenta_id'));

        $this->datosMasivosProcesados = $this->requestDatosMasivos($request)
            ->filtrarLineasValidas($request)
            ->procesaLineas($request)
            ->filtraLineasExistentes($request)
            ->getDatosMasivos();

        return $this->getDatosMasivos();
    }

    protected function requestDatosMasivos(Request $request): GastosParser
    {
        $this->datosMasivos = collect(explode(PHP_EOL, $request->input('datos')));

        return $this;
    }

    protected function filtrarLineasValidas(Request $request): GastosParser
    {
        return $this;
    }

    protected function filtraLineasExistentes(Request $request): GastosParser
    {
        $camposFiltro = ['cuenta_id', 'anno', 'fecha', 'serie', 'monto'];

        $this->datosMasivosProcesados = $this->datosMasivosProcesados
            ->filter(fn ($gasto) => Gasto::where($gasto->only($camposFiltro))->count() == 0);

        return $this;
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
        if (is_null($this->datosMasivosProcesados) or $this->datosMasivosProcesados->isEmpty()) {
            return false;
        }

        return $this->datosMasivosProcesados->count() == $this->datosMasivosProcesados
            ->filter->hasTipoGasto()
            ->count();
    }

    /** @return Collection<array-key, Gasto>  */
    public function getDatosMasivos(): Collection
    {
        return $this->datosMasivosProcesados;
    }

    public function __toString(): string
    {
        return $this->descripcion;
    }

    protected function procesaLineas(Request $request): GastosParser
    {
        $this->datosMasivosProcesados = $this->datosMasivos
            ->map(fn ($linea) => $this->procesaLineaMasivo($request, $linea));

        return $this;
    }

    protected function procesaLineaMasivo(Request $request, string $linea): Gasto
    {
        $linea = collect(explode($this->separadorCampos, $linea))
            ->map(fn ($linea) => trim($linea));

        $tipoGasto = $this->getTipoGasto($request, $linea);

        return new Gasto([
            'cuenta_id' => $request->input('cuenta_id'),
            'anno' => $request->input('anno'),
            'mes' => $request->input('mes'),
            'fecha' => $this->getFecha($linea),
            'serie' => $this->getSerie($linea),
            'glosa' => $this->getGlosa($linea),
            'tipo_gasto_id' => $tipoGasto->id,
            'tipo_movimiento_id' => optional($tipoGasto->tipoMovimiento)->id,
            'monto' => $this->getMonto($linea),
            'usuario_id' => auth()->id(),
        ]);
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getTipoGasto(Request $request, Collection $linea): TipoGasto
    {
        return $this->glosasTipoGasto
            ->first->hasGlosa($this->getGlosa($linea))
            ->tipoGasto
            ?? new TipoGasto();
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getFecha(Collection $linea): Carbon
    {
        return new Carbon();
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getSerie(Collection $linea): string
    {
        return '';
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getGlosa(Collection $linea): string
    {
        return '';
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getMonto(Collection $linea): int
    {
        return 0;
    }
}
