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
    Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
    Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
});

// Inventario Config
Route::group(['prefix' => 'inventario_config', 'as' => 'inventarioConfig.', 'namespace' => 'Inventario', 'middleware' => 'auth'], function () {
    Route::get('{modelName?}', 'ConfigController@index')->name('index');
    Route::get('{modelName}/create', 'ConfigController@create')->name('create');
    Route::post('{modelName}', 'ConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
    Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
});

// Inventario
Route::group(['prefix' => 'inventario', 'as' => 'inventario.', 'namespace' => 'Inventario', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::get('ingresar', 'DigitacionController@index')->name('index');
    Route::post('ingresar', 'DigitacionController@store')->name('store');
    Route::get('nueva-linea/{hoja}/{id?}', 'DigitacionController@add')->name('add');
    Route::post('nueva-linea/{hoja}/{id?}', 'DigitacionController@edit')->name('edit');
    Route::delete('nueva-linea/{hoja}/{id}', 'DigitacionController@destroy')->name('destroy');
    Route::get('ajax-catalogo/{filtro?}', 'DigitacionController@ajaxCatalogos')->name('ajaxCatalogos');

    // Reportes
    Route::get('reporte/{tipo?}', 'ReportesController@reporte')->name('reporte');

    // Ajustes
    Route::get('ajustes', 'AjustesController@ajustes')->name('ajustes');
    Route::post('ajustes', 'AjustesController@update')->name('update');
    Route::get('subir-stock', 'AjustesController@subirStockForm')->name('subirStockForm');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// DB::listen(function ($query) {
//     var_dump($query->sql, $query->bindings);
// });

