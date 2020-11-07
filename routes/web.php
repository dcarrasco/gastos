<?php

use App\Http\Controllers\Gastos\Ingreso;
use App\Http\Controllers\Gastos\Reporte;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Orm\OrmController;
use App\Http\Controllers\Acl\LoginController;
use App\Http\Controllers\Gastos\IngresoMasivo;
use App\Http\Controllers\Gastos\IngresoInversion;
use App\Http\Controllers\Gastos\ReporteTotalGastos;
use App\Http\Controllers\Acl\ConfigController as AclConfigController;
use App\Http\Controllers\Gastos\ConfigController as GastosConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

// ACL Config
OrmController::routes('acl', AclConfigController::class);

// Gastos Config
OrmController::routes('gastos', GastosConfigController::class);

// Gastos
Route::group(['prefix' => 'gastos', 'as' => 'gastos.', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::get('ingresar', [Ingreso::class, 'index'])->name('showMes');
    Route::post('ingresar', [Ingreso::class, 'store'])->name('addGasto');
    Route::delete('ingresar/{gasto}', [Ingreso::class, 'destroy'])->name('borrarGasto');

    Route::get('reporte', [Reporte::class, 'index'])->name('reporte');
    Route::get('reporte/detalle', [Reporte::class, 'show'])->name('detalle');
    Route::get('reporte-total-gastos', [ReporteTotalGastos::class, 'index'])->name('reporteTotalGastos');

    Route::any('ingreso-masivo', [IngresoMasivo::class, 'index'])->name('ingresoMasivo');
    Route::post('ingreso-masivo-add', [IngresoMasivo::class, 'store'])->name('ingresoMasivoAdd');

    Route::get('inversion', [IngresoInversion::class, 'index'])->name('ingresoInversion');
    Route::post('inversion', [IngresoInversion::class, 'store'])->name('addInversion');
    Route::delete('inversion/{gasto}', [IngresoInversion::class, 'destroy'])->name('borrarInversion');
});

// ACL
Route::group(['prefix' => 'acl', 'as' => 'acl.'], function () {
    Route::get('cambia-password/{usuario:username}', [LoginController::class, 'showCambiaPassword'])->name('cambiaPassword');
    Route::post('cambia-password/{usuario:username}', [LoginController::class, 'cambiaPassword'])->name('cambiaPassword');
});


Route::group(['prefix' => 'home', 'as' => 'home.', 'middleware' => 'auth'], function () {
    Route::get('', [HomeController::class, 'index'])->name('index');
    Route::get('ajaxCard', [HomeController::class, 'ajaxCard'])->name('ajaxCard');
});

// DB::listen(function ($query) {
//     dump($query->sql, $query->bindings);
// });
