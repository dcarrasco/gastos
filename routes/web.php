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
Route::group(['prefix' => 'acl_config', 'as' => 'aclConfig.', 'middleware' => 'auth'], function () {
    Route::get('{modelName?}', 'AclConfigController@index')->name('index');
    Route::get('{modelName}/create', 'AclConfigController@create')->name('create');
    Route::post('{modelName}', 'AclConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/edit', 'AclConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'AclConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'AclConfigController@destroy')->name('destroy');
});

// Inventario Config
Route::group(['prefix' => 'inventario_config', 'as' => 'inventarioConfig.', 'middleware' => 'auth'], function () {
    Route::get('{modelName?}', 'InventarioConfigController@index')->name('index');
    Route::get('{modelName}/create', 'InventarioConfigController@create')->name('create');
    Route::post('{modelName}', 'InventarioConfigController@store')->name('store');
    Route::get('{modelName}/{modelID}/edit', 'InventarioConfigController@edit')->name('edit');
    Route::put('{modelName}/{modelID}', 'InventarioConfigController@update')->name('update');
    Route::delete('{modelName}/{modelID}', 'InventarioConfigController@destroy')->name('destroy');
});

// Inventario
Route::group(['prefix' => 'inventario', 'as' => 'inventario.', 'middleware' => 'auth'], function () {
    Route::get('ingresar', 'InventarioController@index')->name('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// DB::listen(function ($query) {
//     var_dump($query->sql, $query->bindings);
// });

