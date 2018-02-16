<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Usuario::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'nombre' => $faker->name,
        'tipo' => 'DIG',
        'activo' => 1,
        'usr' => $faker->firstNameMale,
        'pwd' => $password ?: $password = bcrypt('secret'),
        'correo' => $faker->unique()->safeEmail,
        'fecha_login' => $faker->dateTime,
        'ip_login' => $faker->localIpv4,
        'agente_login' => '',
        'login_errors' => 0,
        'remember_token' => str_random(10),
    ];
});

// -----------------------------------------------------------------------------
// Inventario
// -----------------------------------------------------------------------------
$factory->define(App\Inventario\Auditor::class, function(Faker\Generator $faker) {
    return [
        'nombre' => $faker->name,
        'activo' => 1,
    ];
});

$factory->define(App\Inventario\Familia::class, function(Faker\Generator $faker) {
    $tipo = $faker->boolean(20) ? 'FAM' : 'SUBFAM';

    return [
        'codigo' => strtoupper(str_random($tipo === 'FAM' ? 5 : 8)),
        'tipo' => $tipo,
        'nombre' => strtoupper($faker->words(2, true)),
    ];
});

$factory->define(App\Inventario\Catalogo::class, function(Faker\Generator $faker) {
    return [
        'catalogo' => $faker->unique()->numberBetween(13000000, 13099999),
        'descripcion' => strtoupper($faker->words(3, true)),
        'pmp' => $faker->numberBetween(0, 20000),
        'es_seriado' => 0,
    ];
});

$factory->define(App\Inventario\TipoInventario::class, function(Faker\Generator $faker) {
    return [
        'id_tipo_inventario' => strtoupper($faker->unique()->lexify('?????')),
        'desc_tipo_inventario' => strtoupper($faker->words(3, true)),
    ];
});

$factory->define(App\Inventario\Inventario::class, function(Faker\Generator $faker) {
    return [
        'nombre' => strtoupper($faker->words(3, true)),
        'activo' => 1,
        'tipo_inventario' => App\Inventario\TipoInventario::all()->shuffle()->first()->id_tipo_inventario,
    ];
});

$factory->define(App\Inventario\TipoUbicacion::class, function(Faker\Generator $faker) {
    return [
        'tipo_inventario' => App\Inventario\TipoInventario::all()->shuffle()->first()->id_tipo_inventario,
        'tipo_ubicacion' => strtoupper($faker->words(2, true)),
    ];
});

$factory->define(App\Inventario\Centro::class, function(Faker\Generator $faker) {
    return [
        'centro' => strtoupper($faker->unique()->lexify('????')),
    ];
});

$factory->define(App\Inventario\Almacen::class, function(Faker\Generator $faker) {
    return [
        'almacen' => strtoupper($faker->unique()->lexify('????')),
    ];
});

$factory->define(App\Inventario\UnidadMedida::class, function(Faker\Generator $faker) {
    return [
        'unidad' => strtoupper($faker->unique()->lexify('????')),
        'desc_unidad' => strtoupper($faker->words(1, true)),
    ];
});

$factory->define(App\Inventario\DetalleInventario::class, function(Faker\Generator $faker) {
    $catalogo = App\Inventario\Catalogo::all()->shuffle()->first();
    return [
        'id_inventario' => App\Inventario\Inventario::first()->id,
        'hoja' => $faker->numberBetween(1, 50),
        'ubicacion' => strtoupper($faker->unique()->lexify('????')),
        'hu' => strtoupper($faker->unique()->lexify('??????')),
        'catalogo' => $catalogo->catalogo,
        'descripcion' => $catalogo->descripcion,
        'lote' => 'NUEVO',
        'centro' => App\Inventario\Centro::first()->centro,
        'almacen' => App\Inventario\Almacen::first()->almacen,
        'um' => App\Inventario\UnidadMedida::first()->unidad,
        'stock_sap' => $faker->numberBetween(1, 100),
        'stock_fisico' => $faker->numberBetween(1, 100),
        'digitador' => 1,
        'auditor' => App\Inventario\Auditor::first()->id,
        'reg_nuevo' => 'N',
        'fecha_modificacion' => \Carbon\Carbon::now(),
    ];
});

// -----------------------------------------------------------------------------
// TOA
// -----------------------------------------------------------------------------
$factory->define(App\Toa\Tecnico::class, function(Faker\Generator $faker) {
    return [
        'id_tecnico' => strtoupper($faker->unique()->lexify('????????')),
        'tecnico' => $faker->name,
        'rut' => $faker->numerify('##.###.###-#'),
    ];
});

$factory->define(App\Toa\Empresa::class, function(Faker\Generator $faker) {
    return [
        'id_empresa' => strtoupper($faker->unique()->lexify('?????')),
        'empresa' => strtoupper($faker->words(3, true)),
    ];
});

$factory->define(App\Toa\TipMaterialTrabajo::class, function(Faker\Generator $faker) {
    return [
        'desc_tip_material' => strtoupper($faker->words(3, true)),
        'color' => strtoupper($faker->words(1, true)),
    ];
});

$factory->define(App\Toa\TipoTrabajo::class, function(Faker\Generator $faker) {
    return [
        'id_tipo' => strtoupper($faker->unique()->lexify('??????')),
        'desc_tipo' => strtoupper($faker->words(5, true)),
    ];
});

$factory->define(App\Toa\Ciudad::class, function(Faker\Generator $faker) {
    return [
        'id_ciudad' => strtoupper($faker->unique()->lexify('????')),
        'ciudad' => strtoupper($faker->unique()->city),
        'orden' => $faker->unique()->numberBetween(0, 100),
    ];
});
