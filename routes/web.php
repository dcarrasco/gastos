<?php

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
Route::group(['prefix' => 'acl_config', 'as' => 'aclConfig.', 'namespace' => 'Acl', 'middleware' => 'auth'], function () {
    Route::get('{modelName?}', 'ConfigController@index')->name('index');
    Route::get('{modelName}/create', 'ConfigController@create')->name('create');
    Route::post('{modelName}', 'ConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/show', 'ConfigController@show')->name('show');
    Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
    Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
});

// Gastos Config
Route::group(['prefix' => 'gastos-config', 'as' => 'gastosConfig.', 'namespace' => 'Gastos', 'middleware' => 'auth'], function () {
    Route::get('ajaxCard/{modelName}', 'ConfigController@ajaxCard')->name('ajaxCard');
    Route::get('{modelName?}', 'ConfigController@index')->name('index');
    Route::get('{modelName}/create', 'ConfigController@create')->name('create');
    Route::post('{modelName}', 'ConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/show', 'ConfigController@show')->name('show');
    Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
    Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
});

// Gastos
Route::group(['prefix' => 'gastos', 'as' => 'gastos.', 'namespace' => 'Gastos', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::get('ingresar', 'Ingreso@showMes')->name('showMes');
    Route::post('ingresar', 'Ingreso@addGasto')->name('addGasto');
    Route::delete('borrar', 'Ingreso@borrarGasto')->name('borrarGasto');
    Route::get('reporte', 'Reporte@reporte')->name('reporte');
    Route::get('detalle', 'Reporte@detalle')->name('detalle');
    Route::any('ingreso-masivo', 'IngresoMasivo@ingresoMasivo')->name('ingresoMasivo');
    Route::post('ingreso-masivo-add', 'IngresoMasivo@addGastosMasivos')->name('ingresoMasivoAdd');
    Route::get('ingreso-inversion', 'IngresoInversion@formularioIngreso')->name('ingresoInversion');
    Route::post('ingreso-inversion', 'IngresoInversion@addInversion')->name('addInversion');
});

// ACL
Route::group(['prefix'=>'acl', 'as'=>'acl.', 'namespace'=>'Acl'], function() {
    Route::get('cambia-password', 'LoginController@showCambiaPassword')->name('cambiaPassword');
    Route::post('cambia-password', 'LoginController@cambiaPassword')->name('cambiaPassword');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// DB::listen(function ($query) {
//     dump($query->sql, $query->bindings);
// });

