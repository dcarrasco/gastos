<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Controller;
use App\Models\Cash\Cuenta;
use App\Models\Cash\Movimiento;
use App\Http\Requests\Cash\AddMovimientoRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Ingreso extends Controller
{
    public function index(Request $request): View
    {
        $cuentas = Cuenta::resumenCuentas();

        return view('cash.index', compact('cuentas'));
    }


    public function show(Request $request, Cuenta $cuenta): View
    {
        $movimientos = Movimiento::showCuenta($cuenta);
        $selectCuentas = Cuenta::selectCuentas();
        $tiposCargo = Movimiento::selectTiposCargo($cuenta);
        $movimiento = null;

        return view('cash.show', compact('movimientos', 'cuenta', 'selectCuentas', 'tiposCargo', 'movimiento'));
    }


    public function store(AddMovimientoRequest $request): RedirectResponse
    {
        $validated = collect($request->validated());
        $movimiento_id = Movimiento::getUUID();
        $conciliado = "n";
        $balance = 0;

        $movimiento1 = $validated->merge(compact('movimiento_id', 'conciliado', 'balance'))->all();

        $movimiento2 = [
            'cuenta_id' => $movimiento1['contracuenta_id'],
            'fecha' => $movimiento1['fecha'],
            'descripcion' => $movimiento1['descripcion'],
            'numero' => $movimiento1['numero'] ?? null,
            'contracuenta_id' => $movimiento1['cuenta_id'],
            'tipo_cargo' => $movimiento1['tipo_cargo'] == "A" ? "C" : "A",
            'monto' => $movimiento1['monto'],
            'movimiento_id' => $movimiento1['movimiento_id'],
            'conciliado' => $movimiento1['conciliado'],
            'balance' => $movimiento1['balance'],
        ];

        Movimiento::create($movimiento1);
        Movimiento::create($movimiento2);

        return redirect()->route('cash.show', ['cuenta' => $movimiento1["cuenta_id"]]);
    }


    public function showMovimiento(Request $request, Cuenta $cuenta, Movimiento $movimiento)
    {
        $movimientos = Movimiento::showCuenta($cuenta);
        $selectCuentas = Cuenta::selectCuentas();
        $tiposCargo = Movimiento::selectTiposCargo($cuenta);

        return view('cash.show', compact('movimientos', 'cuenta', 'selectCuentas', 'tiposCargo', 'movimiento'));
    }

    public function update(AddMovimientoRequest $request, Cuenta $cuenta, Movimiento $movimiento): RedirectResponse
    {
        $validated = collect($request->validated());

        $movimiento->fecha = $validated->get('fecha');
        $movimiento->numero = $validated->get('numero');
        $movimiento->descripcion = $validated->get('descripcion');
        $movimiento->contracuenta_id = $validated->get('contracuenta_id');
        $movimiento->tipo_cargo = $validated->get('tipo_cargo');
        $movimiento->monto = $validated->get('monto');
        $movimiento->save();

        $movimiento2 = $movimiento->contraMovimiento();
        $movimiento2->fecha = $validated->get('fecha');
        $movimiento2->numero = $validated->get('numero');
        $movimiento2->descripcion = $validated->get('descripcion');
        $movimiento2->contracuenta_id = $cuenta->id;
        $movimiento2->tipo_cargo = $validated->get('tipo_cargo') == "A" ? "C" : "A";
        $movimiento2->monto = $validated->get('monto');
        $movimiento2->save();

        return redirect()->route('cash.show', ['cuenta' => $cuenta]);
    }


    public function destroy(DeleteGastoRequest $request, Gasto $gasto): RedirectResponse
    {
        $gasto->delete();

        return redirect()->route('gastos.showMes', $request->only(['cuenta_id', 'anno', 'mes']));
    }
}
