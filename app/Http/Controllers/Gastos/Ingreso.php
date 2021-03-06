<?php

namespace App\Http\Controllers\Gastos;

use App\Models\Gastos\Gasto;
use Illuminate\Http\Request;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\SaldoMes;
use App\Models\Gastos\TipoGasto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddGastoRequest;
use App\Http\Requests\Gasto\DeleteGastoRequest;

class Ingreso extends Controller
{
    public function index(Request $request)
    {
        $selectCuentas = Cuenta::selectCuentasGastos();
        $selectTiposGastos = TipoGasto::selectOptions();

        $cuentaId = $request->input('cuenta_id', $selectCuentas->keys()->first() ?? 0);
        $anno = $request->input('anno', today()->year);
        $mes = $request->input('mes', today()->month);

        $movimientosMes = Gasto::movimientosMes($cuentaId, $anno, $mes);

        if ($request->recalcula === 'recalcula') {
            SaldoMes::recalculaSaldoMes($cuentaId, $anno, $mes);
        }

        return view('gastos.gastos-index', compact('selectCuentas', 'selectTiposGastos', 'movimientosMes'));
    }

    public function store(AddGastoRequest $request)
    {
        Gasto::createGasto($request->validated());
        SaldoMes::recalculaSaldoMes($request->cuenta_id, $request->anno, $request->mes);

        return redirect()->route('gastos.showMes', $request->only(['cuenta_id', 'anno', 'mes']));
    }

    public function destroy(DeleteGastoRequest $request, Gasto $gasto)
    {
        $gasto->delete();

        return redirect()->route('gastos.showMes', $request->only(['cuenta_id', 'anno', 'mes']));
    }
}
