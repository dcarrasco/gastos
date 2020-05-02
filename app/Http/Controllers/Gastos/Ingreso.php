<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\SaldoMes;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddGastoRequest;

class Ingreso extends Controller
{
    public function index(Request $request)
    {
        $currentLocale = setlocale(LC_TIME, 'es-ES');

        $selectCuentas = Cuenta::selectCuentasGastos();
        $selectTiposGastos = TipoGasto::selectOptions();

        $cuentaId = $request->input('cuenta_id', $selectCuentas->keys()->first());
        $anno = $request->input('anno', today()->year);
        $mes = $request->input('mes', today()->month);

        $movimientosMes = Gasto::movimientosMes($cuentaId, $anno, $mes);

        if ($request->recalcula === 'recalcula') {
            SaldoMes::recalculaSaldoMes($cuentaId, $anno, $mes);
        }

        return view('gastos.gastos.index', compact('selectCuentas', 'selectTiposGastos', 'movimientosMes'));
    }

    public function store(AddGastoRequest $request)
    {
        Gasto::createGasto($request->validated());
        SaldoMes::recalculaSaldoMes($request->cuenta_id, $request->anno, $request->mes);

        return redirect()->route('gastos.showMes', $request->only([
            'cuenta_id', 'anno', 'mes'
        ]));
    }

    public function borrarGasto(Request $request)
    {
        Gasto::findOrFail($request->id)->delete();

        return redirect()->route('gastos.showMes', $request->only([
            'cuenta_id', 'anno', 'mes'
        ]));
    }
}
