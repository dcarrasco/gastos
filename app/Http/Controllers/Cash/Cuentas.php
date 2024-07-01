<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Controller;
use App\Models\Cash\Cuenta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Cuentas extends Controller
{
    public function index(Request $request): View
    {
        $cuentas = Cuenta::resumenCuentas();

        return view('cash.cuentas.index', compact('cuentas'));
    }
}
