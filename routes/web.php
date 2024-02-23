<?php

use App\Http\Controllers\Cash\ConfigController as CashConfigController;
use App\Http\Controllers\Cash\Ingreso as CashIngreso;
use App\Http\Controllers\Acl\ConfigController as AclConfigController;
use App\Http\Controllers\Acl\LoginController;
use App\Http\Controllers\Gastos\ConfigController as GastosConfigController;
use App\Http\Controllers\Gastos\Ingreso;
use App\Http\Controllers\Gastos\IngresoInversion;
use App\Http\Controllers\Gastos\IngresoMasivo;
use App\Http\Controllers\Gastos\Reporte;
use App\Http\Controllers\Gastos\ReporteTotalGastos;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Orm\OrmController;
use Illuminate\Support\Facades\Route;

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

// Cash Config
OrmController::routes('cash', CashConfigController::class);

// Gastos
Route::group(['prefix' => 'gastos', 'as' => 'gastos.', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::controller(Ingreso::class)->group(function () {
        Route::get('ingresar', 'index')->name('showMes');
        Route::post('ingresar', 'store')->name('addGasto');
        Route::delete('ingresar/{gasto}', 'destroy')->name('borrarGasto');
    });

    Route::get('reporte', [Reporte::class, 'index'])->name('reporte');
    Route::get('reporte/detalle', [Reporte::class, 'show'])->name('detalle');
    Route::get('reporte-total-gastos', [ReporteTotalGastos::class, 'index'])->name('reporteTotalGastos');

    Route::controller(IngresoMasivo::class)->group(function () {
        Route::match(['get', 'post'], 'ingreso-masivo', 'index')->name('ingresoMasivo');
        Route::post('ingreso-masivo-add', 'store')->name('ingresoMasivoAdd');
        Route::post('ingreso-masivo-add-tipo-gasto', 'storeTipoGasto')->name('ingresoMasivoAddTipoGasto');
    });

    Route::controller(IngresoInversion::class)->group(function () {
        Route::get('inversion', 'index')->name('ingresoInversion');
        Route::post('inversion', 'store')->name('addInversion');
        Route::delete('inversion/{gasto}', 'destroy')->name('borrarInversion');
    });
});

// ACL
Route::group(['prefix' => 'acl', 'as' => 'acl.'], function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('cambia-password/{usuario:username}', 'showCambiaPassword')->name('showCambiaPassword');
        Route::post('cambia-password/{usuario:username}', 'cambiaPassword')->name('cambiaPassword');
    });
});

// Cash
Route::group(['prefix' => 'cash', 'as' => 'cash.', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::controller(CashIngreso::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('show/{cuenta}', 'show')->name('show');
        Route::post('show/{cuenta}', 'store')->name('store');
        Route::get('show-movimiento/{cuenta}/{movimiento}', 'showMovimiento')->name('showMovimiento');
        Route::put('show-movimiento/{cuenta}/{movimiento}', 'update')->name('update');

        Route::post('ingresar', 'store')->name('addGasto');
        Route::delete('ingresar/{gasto}', 'destroy')->name('borrarGasto');
    });
});

Route::group(['prefix' => 'home', 'as' => 'home.', 'middleware' => 'auth'], function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('ajaxCard', 'ajaxCard')->name('ajaxCard');
    });
});

// DB::listen(function ($query) {
//     dump($query->sql, $query->bindings);
// });
