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
    public function index(Request $request, Cuenta $cuenta): View
    {
        $movimientos = Movimiento::showCuenta($cuenta);
        $selectCuentas = Cuenta::selectCuentas();
        $tiposCargo = Movimiento::selectTiposCargo($cuenta);

        return view('cash.movimientos.index',
            compact('movimientos', 'cuenta', 'selectCuentas', 'tiposCargo')
        );
    }


    public function create(Request $request, Cuenta $cuenta): View
    {
        $movimientos = Movimiento::showCuenta($cuenta);
        $selectCuentas = Cuenta::selectCuentas();
        $tiposCargo = Movimiento::selectTiposCargo($cuenta);
        $movimiento = null;

        return view('cash.movimientos.show',
            compact('movimientos', 'cuenta', 'selectCuentas', 'tiposCargo', 'movimiento')
        );
    }


    public function show(Request $request, Cuenta $cuenta, Movimiento $movimiento): View
    {
        $movimientos = Movimiento::showCuenta($cuenta);
        $selectCuentas = Cuenta::selectCuentas();
        $tiposCargo = Movimiento::selectTiposCargo($cuenta);

        return view('cash.movimientos.show',
            compact('movimientos', 'cuenta', 'selectCuentas', 'tiposCargo', 'movimiento')
        );
    }


    public function store(AddMovimientoRequest $request, Cuenta $cuenta): RedirectResponse
    {
        $movimiento = new Movimiento(array_merge($request->validated(), Movimiento::initAttrib()));
        $contraMovimiento = $movimiento->makeContraMovimiento();

        $movimiento->save();
        $contraMovimiento->save();

        return redirect()->route('cashMovimientos.index', compact('cuenta'));
    }


    public function update(AddMovimientoRequest $request, Cuenta $cuenta, Movimiento $movimiento): RedirectResponse
    {
        $movimiento->fill($request->validated());
        $movimiento->save();

        $contraMovimiento = $movimiento->getContraMovimiento();
        $contraMovimiento->fillContraMovimiento($request->validated(), $movimiento);
        $contraMovimiento->save();

        return redirect()->route('cashMovimientos.index', compact('cuenta'));
    }


    public function destroy(DeleteGastoRequest $request, Gasto $gasto): RedirectResponse
    {
        //
    }
}
