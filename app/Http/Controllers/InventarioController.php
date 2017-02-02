<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;

class InventarioController extends Controller
{
    public function index()
    {
        $inventario = Inventario::getInventarioActivo();

        dump(Inventario::getIDInventarioActivo());
        dump(Inventario::getInventarioActivo());

        return view('inventario.inventario');
    }
}
