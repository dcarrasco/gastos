<?php

use Faker\Generator;
use Illuminate\Support\Str;

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
$factory->define(App\User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Acl\Usuario::class, function (Generator $faker) {
    static $password;

    return [
        'nombre' => $faker->name,
        'activo' => $faker->boolean(80) ? 1 : 0,
        'username' => $faker->firstNameMale,
        'password' => $password ?: $password = bcrypt('secret'),
        'email' => $faker->unique()->safeEmail,
        'fecha_login' => $faker->dateTime,
        'ip_login' => $faker->localIpv4,
        'agente_login' => '',
        'login_errors' => 0,
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Acl\App::class, function (Generator $faker) {
    return [
        'app' => $faker->words(3, true),
        'descripcion' => $faker->sentence(),
        'url' => $faker->url,
        'icono' => $faker->word,
        'orden' => $faker->randomDigit,
    ];
});

$factory->define(App\Acl\Rol::class, function (Generator $faker) {
    return [
        'app_id' => 0,
        'rol' => $faker->words(3, true),
        'descripcion' => $faker->sentence(),
    ];
});

$factory->define(App\Acl\Modulo::class, function (Generator $faker) {
    return [
        'app_id' => 0,
        'modulo' => $faker->words(3, true),
        'descripcion' => $faker->sentence(),
        'llave_modulo' => Str::random(10),
        'icono' => $faker->word,
        'url' => $faker->url,
        'orden' => $faker->randomDigit,
        'created_at' => now(),
    ];
});

$factory->define(App\Gastos\Banco::class, function (Generator $faker) {
    return [
        'nombre' => $faker->company,
    ];
});

$factory->define(App\Gastos\Cuenta::class, function (Generator $faker) {
    return [
        'cuenta' => $faker->words(3, true),
    ];
});

$factory->define(App\Gastos\TipoCuenta::class, function (Generator $faker) {
    return [
        'tipo_cuenta' => $faker->words(3, true),
        'tipo' => App\Gastos\TipoCuenta::CUENTA_GASTO
    ];
});

$factory->define(App\Gastos\TipoMovimiento::class, function (Generator $faker) {
    return [
        'tipo_movimiento' => $faker->words(3, true),
        'signo' => -1,
        'orden' => 10,
    ];
});

$factory->define(App\Gastos\TipoGasto::class, function (Generator $faker) {
    return [
        'tipo_gasto' => $faker->words(3, true),
    ];
});

$factory->define(App\Gastos\Gasto::class, function (Generator $faker) {
    return [
        'anno' => now()->year,
        'mes' => now()->month,
        'fecha' => now(),
        'glosa' => $faker->words(5, true),
        'serie' => $faker->ean8(),
        'monto' => $faker->numberBetween(0, 10000),
    ];
});
