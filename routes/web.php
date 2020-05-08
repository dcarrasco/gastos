<?php

use App\Http\Controllers\Orm\OrmController;

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

// ACL Config
OrmController::routes('acl');

// Gastos Config
OrmController::routes('gastos');

// Gastos
Route::group(['prefix' => 'gastos', 'as' => 'gastos.', 'namespace' => 'Gastos', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::get('ingresar', 'Ingreso@index')->name('showMes');
    Route::post('ingresar', 'Ingreso@store')->name('addGasto');
    Route::delete('borrar', 'Ingreso@borrarGasto')->name('borrarGasto');
    Route::get('reporte', 'Reporte@index')->name('reporte');
    Route::get('reporte/detalle', 'Reporte@show')->name('detalle');
    Route::get('reporte-total-gastos', 'ReporteTotalGastos@index')->name('reporteTotalGastos');
    Route::any('ingreso-masivo', 'IngresoMasivo@index')->name('ingresoMasivo');
    Route::post('ingreso-masivo-add', 'IngresoMasivo@store')->name('ingresoMasivoAdd');
    Route::get('inversion', 'IngresoInversion@index')->name('ingresoInversion');
    Route::post('inversion', 'IngresoInversion@store')->name('addInversion');
});

// ACL
Route::group(['prefix' => 'acl', 'as' => 'acl.', 'namespace' => 'Acl'], function () {
    Route::get('cambia-password/{usuario:username}', 'LoginController@showCambiaPassword')->name('cambiaPassword');
    Route::post('cambia-password/{usuario:username}', 'LoginController@cambiaPassword')->name('cambiaPassword');
});

Auth::routes();

Route::group(['prefix' => 'home', 'as' => 'home.', 'middleware' => 'auth'], function () {
    Route::get('', 'HomeController@index')->name('index');
    Route::get('ajaxCard', 'HomeController@ajaxCard')->name('ajaxCard');
});

// DB::listen(function ($query) {
//     dump($query->sql, $query->bindings);
// });
