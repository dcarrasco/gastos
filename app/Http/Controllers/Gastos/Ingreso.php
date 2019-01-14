<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\SaldoMes;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddGastoRequest;

class Ingreso extends Controller
{
    public function showMes(Request $request)
    {
        if ($request->recalcula === 'recalcula') {
            (new SaldoMes)->recalculaSaldoMes($request);
        }

        return view('gastos.showmes', [
            'formCuenta' => Cuenta::formArrayGastos(),
            'formAnno' => Cuenta::getFormAnno(),
            'annoDefault' => Carbon::now()->year,
            'formMes' => Cuenta::getFormMes(),
            'mesDefault' => Carbon::now()->month,
            'movimientosMes' => Gasto::movimientosMes($request),
            'formTipoGasto' => TipoGasto::formArray(),
            'saldoMesAnterior' => SaldoMes::getSaldoMesAnterior($request),
        ]);
    }

    public function addGasto(AddGastoRequest $request)
    {
        $gasto = new Gasto($request->all());

        $gasto->tipo_movimiento_id = $gasto->tipoGasto->tipo_movimiento_id;
        $gasto->fecha = empty($gasto->fecha)
            ? Carbon::createMidnightDate($request->anno, $request->mes, 1)
            : $gasto->fecha;
        $gasto->usuario_id = auth()->id();

        $gasto->save();

        $saldoMesAnterior = (new SaldoMes)->recalculaSaldoMes($request);

        return redirect()->route('gastos.showMes', $request->only([
            'cuenta_id', 'anno', 'mes'
        ]));
    }

    public function borrarGasto(Request $request)
    {
        $gasto = Gasto::findOrFail($request->id)->delete();

        return redirect()->route('gastos.showMes', $request->only([
            'cuenta_id', 'anno', 'mes'
        ]));
    }
}
