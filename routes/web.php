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

// Inventario Config
Route::group(['prefix' => 'inventario-config', 'as' => 'inventarioConfig.', 'namespace' => 'Inventario', 'middleware' => 'auth'], function () {
    Route::get('{modelName?}', 'ConfigController@index')->name('index');
    Route::get('{modelName}/create', 'ConfigController@create')->name('create');
    Route::post('{modelName}', 'ConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/show', 'ConfigController@show')->name('show');
    Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
    Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
});

// Stock Config
Route::group(['prefix' => 'stock-config', 'as' => 'stockConfig.', 'namespace' => 'Stock', 'middleware' => 'auth'], function () {
    Route::get('{modelName?}', 'ConfigController@index')->name('index');
    Route::get('{modelName}/create', 'ConfigController@create')->name('create');
    Route::post('{modelName}', 'ConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/show', 'ConfigController@show')->name('show');
    Route::get('{modelName}/{modelID}/edit', 'ConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'ConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'ConfigController@destroy')->name('destroy');
    Route::get('{modelName}/ajax-form', 'ConfigController@ajaxOnChange')->name('ajaxOnChange');
});

// TOA Config
Route::group(['prefix' => 'toa-config', 'as' => 'toaConfig.', 'namespace' => 'Toa', 'middleware' => 'auth'], function () {
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
    Route::get('ingresar', 'IngresoGastos@showMes')->name('showMes');
    Route::post('ingresar', 'IngresoGastos@addGasto')->name('addGasto');
});



// Inventario
Route::group(['prefix' => 'inventario', 'as' => 'inventario.', 'namespace' => 'Inventario', 'middleware' => 'auth'], function () {
    // Digitacion
    Route::get('ingresar/{hoja?}', 'DigitacionController@showHoja')->name('showHoja');
    Route::post('ingresar/{hoja?}', 'DigitacionController@updateHoja')->name('updateHoja');
    Route::get('nueva-linea/{hoja}/{id?}', 'DigitacionController@addLinea')->name('addLinea');
    Route::post('nueva-linea/{hoja}/{id?}', 'DigitacionController@editLinea')->name('editLinea');
    Route::delete('nueva-linea/{hoja}/{id}', 'DigitacionController@destroyLinea')->name('destroyLinea');
    Route::get('ajax-catalogo/{filtro?}', 'DigitacionController@ajaxCatalogos')->name('ajaxCatalogos');

    // Reportes
    Route::get('reporte/{tipo?}', 'ReportesController@reporte')->name('reporte');

    // Ajustes
    Route::get('ajustes', 'AjustesController@showForm')->name('ajustes');
    Route::post('ajustes', 'AjustesController@update')->name('update');
    Route::get('subir-stock', 'SubirStockController@upload')->name('upload');
    Route::post('subir-stock', 'SubirStockController@upload')->name('upload');
    Route::post('subir-linea', 'SubirStockController@uploadLinea')->name('uploadLinea');
    Route::get('imprimir', 'ImprimirController@showForm')->name('imprimirForm');
    Route::post('imprimir', 'ImprimirController@imprimir')->name('imprimir');
});

// Stock
Route::group(['prefix' => 'stock', 'as' => 'stock.', 'namespace' => 'Stock', 'middleware' => 'auth'], function () {
    // Analisis
    Route::any('analisis-series', 'AnalisisController@analisisSeries')->name('analisisSeries');
    // Consulta sotck
    Route::any('consulta-stock-movil', 'ConsultaStockController@consultaStockMovil')->name('consultaStockMovil');
    Route::any('consulta-stock-fija', 'ConsultaStockController@consultaStockFija')->name('consultaStockFija');
    Route::get('consulta-fechas/{tipoOp}/{tipoFecha}', 'ConsultaStockController@ajaxFecha');
    Route::get('consulta-almacenes/{tipoOp}/{tipoAlm}', 'ConsultaStockController@ajaxAlmacenes');
});

// TOA
Route::group(['prefix' => 'toa', 'as' => 'toa.', 'namespace' => 'Toa', 'middleware' => 'auth'], function () {
    // Controles
    Route::get('controles/{tipo?}', 'ControlesController@showFormControles')->name('controles');
    Route::post('controles/{tipo?}', 'ControlesController@getControles')->name('controles');
    // Consumos
    Route::get('consumos', 'ConsumosController@showFormConsumos')->name('consumos');
    Route::post('consumos', 'ConsumosController@getConsumos')->name('consumos');
    Route::get('peticiones/{tipo}/{fechaDesde}/{fechaHasta}/{id}/{id2?}', 'ConsumosController@peticiones')->name('peticiones');
    Route::any('peticion/{idPeticion?}', 'ConsumosController@peticion')->name('peticion');
    // Asignacion Materiales
    Route::get('asignacion', 'AsignacionController@showForm')->name('asignacion');
    Route::post('asignacion', 'AsignacionController@getAsignacion')->name('asignacion');
});

// AdminBD
Route::group(['prefix' => 'adminbd', 'as' => 'adminbd.', 'namespace' => 'AdminBd', 'middleware' => 'auth'], function () {
    Route::get('queries', 'AdminBdController@showQueries')->name('queries');
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

