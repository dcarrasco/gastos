<?php

namespace App\Http\Controllers\Gastos;

use App\Models\Gastos\Gasto;
use Illuminate\Http\Request;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\Inversion;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Gastos\TipoMovimiento;
use App\Http\Requests\Gasto\AddInversionRequest;

class IngresoInversion extends Controller
{
    public function index(Request $request): View
    {
        $cuentas = Cuenta::selectCuentasInversiones();
        $tiposMovimientos = TipoMovimiento::selectOptions();
        $inversion = new Inversion(
            $request->input('cuenta_id', $cuentas->keys()->first()),
            $request->input('anno', today()->year)
        );

        return view('gastos.inversion-index', compact('cuentas', 'tiposMovimientos', 'inversion'));
    }

    public function store(AddInversionRequest $request): RedirectResponse
    {
        Gasto::createInversion($request->validated());

        return redirect()->route('gastos.ingresoInversion', $request->only('cuenta_id', 'anno'));
    }

    public function destroy(Request $request, Gasto $gasto): RedirectResponse
    {
        $this->authorize('delete', $gasto);

        $gasto->delete();

        return redirect()->route('gastos.ingresoInversion', $request->only('cuenta_id', 'anno'));
    }
}
