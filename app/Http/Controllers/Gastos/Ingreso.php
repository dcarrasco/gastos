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
        $cuenta = new Cuenta;
        $selectCuentas = $cuenta->selectCuentasGastos();
        $selectTiposGastos = TipoGasto::formArray();
        $today = Carbon::now();

        $cuentaId = $request->input('cuenta_id', key($selectCuentas));
        $anno = $request->input('anno', $today->year);
        $mes = $request->input('mes', $today->month);

        $movimientosMes = Gasto::movimientosMes($cuentaId, $anno, $mes);
        $saldoMesAnterior = SaldoMes::getSaldoMesAnterior($cuentaId, $anno, $mes);

        if ($request->recalcula === 'recalcula') {
            (new SaldoMes)->recalculaSaldoMes($cuentaId, $anno, $mes);
        }

        return view('gastos.showmes', compact('today', 'cuenta', 'selectCuentas', 'movimientosMes', 'saldoMesAnterior', 'selectTiposGastos'));
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

        $saldoMesAnterior = (new SaldoMes)->recalculaSaldoMes($request->cuenta_id, $request->anno, $request->mes);

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
