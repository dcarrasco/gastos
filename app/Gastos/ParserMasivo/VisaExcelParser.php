<?php

namespace App\Gastos\ParserMasivo;

use Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Gastos\GlosaTipoGasto;
use Illuminate\Support\Collection;

class VisaExcelParser implements GastosParser
{
    protected $glosasTipoGasto = null;

    protected $datosMasivos = null;


    public function procesaMasivo(Request $request): Collection
    {
        $this->glosasTipoGasto = GlosaTipoGasto::getCuenta($request->cuenta_id);

        return $this->requestDatosMasivos($request)
            ->filtrarLineasValidas($request)
            ->procesaLineas($request)
            ->filtraLineasExistentes($request)
            ->getDatosMasivos();
    }

    protected function getDatosMasivos()
    {
        return $this->datosMasivos;
    }

    protected function requestDatosMasivos(Request $request): VisaExcelParser
    {
        $this->datosMasivos = collect(explode(PHP_EOL, $request->datos));

        return $this;
    }

    protected function filtrarLineasValidas(Request $request): VisaExcelParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->filter(function($linea) {
                return preg_match('/[0-9]{4}/', $linea) === 1;
            });

        return $this;
    }

    protected function procesaLineas(Request $request): VisaExcelParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->map(function($linea) use ($request) {
                return $this->procesaLineaMasivo($request, $linea);
            });

        return $this;
    }

    protected function filtraLineasExistentes(Request $request): VisaExcelParser
    {
        $camposFiltro = ['cuenta_id', 'anno', 'fecha', 'serie', 'monto'];

        $this->datosMasivos = $this->datosMasivos
            ->filter(function ($gasto) use ($request, $camposFiltro) {
                return Gasto::where($gasto->only($camposFiltro))->get()->count() == 0;
            });

        return $this;
    }

    protected function getTipoGasto(Request $request, $linea = ''): TipoGasto
    {
        $glosa = $this->getGlosa($linea);

        $glosaTipoGasto = $this->glosasTipoGasto
            ->first(function($glosaTipoGasto) use ($glosa) {
                return strpos(strtoupper($glosa), strtoupper($glosaTipoGasto->glosa)) !== false;
            })
            ?? new GlosaTipoGasto;

        return $glosaTipoGasto->tipoGasto ?? new TipoGasto;
    }

    protected function procesaLineaMasivo(Request $request, $linea = ''): Gasto
    {
        $linea = collect(explode("\t", $linea));
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

    protected function getFecha(Collection $linea): Carbon
    {
        $fecha = preg_split('/-/', trim($linea[2]));

        return Carbon::create($fecha[2], $fecha[1], $fecha[0], 0, 0, 0);
    }

    protected function getSerie(Collection $linea): string
    {
        return trim($linea[0]);
    }

    protected function getGlosa(Collection $linea): string
    {
        return trim($linea[3]);
    }

    protected function getMonto(Collection $linea): int
    {
        return (int) str_replace('.', '', str_replace('$', '', trim($linea[4])));
    }
}
