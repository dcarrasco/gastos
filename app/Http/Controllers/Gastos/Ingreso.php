<?php

namespace App\Http\Controllers\Gastos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddGastoRequest;
use App\Http\Requests\Gasto\DeleteGastoRequest;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\SaldoMes;
use App\Models\Gastos\TipoGasto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Ingreso extends Controller
{
    public function index(Request $request): View
    {
        $selectCuentas = Cuenta::selectCuentasGastos();
        $selectTiposGastos = TipoGasto::selectOptions();

        $cuentaId = $request->input('cuenta_id', $selectCuentas->keys()->first() ?: 0);
        $anno = $request->input('anno', today()->year);
        $mes = $request->input('mes', today()->month);

        $movimientosMes = Gasto::movimientosMes($cuentaId, $anno, $mes);

        if ($request->input('recalcula') === 'recalcula') {
            SaldoMes::recalculaSaldoMes($cuentaId, $anno, $mes);
        }

        return view('gastos.gastos-index', compact('selectCuentas', 'selectTiposGastos', 'movimientosMes'));
    }

    public function store(AddGastoRequest $request): RedirectResponse
    {
        Gasto::createGasto($request->validated());
        SaldoMes::recalculaSaldoMes($request->input('cuenta_id'), $request->input('anno'), $request->input('mes'));

        return redirect()->route('gastos.showMes', $request->only(['cuenta_id', 'anno', 'mes']));
    }

    public function destroy(DeleteGastoRequest $request, Gasto $gasto): RedirectResponse
    {
        $gasto->delete();

        return redirect()->route('gastos.showMes', $request->only(['cuenta_id', 'anno', 'mes']));
    }
}
