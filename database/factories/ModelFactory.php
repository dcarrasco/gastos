<?php

use Faker\Generator;

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
        'remember_token' => str_random(10),
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
        'remember_token' => str_random(10),
        'created_at' => Carbon\Carbon::now(),
    ];
});

$factory->define(App\Gastos\Banco::class, function (Generator $faker) {
    return [
        'nombre' => $faker->company,
    ];
});

$factory->define(App\Gastos\Cuenta::class, function (Generator $faker) {
    return [
        'cuenta' => $faker->company,
    ];
});

$factory->define(App\Gastos\TipoCuenta::class, function (Generator $faker) {
    return [
        'tipo_cuenta' => $faker->company,
        'tipo' => App\Gastos\TipoCuenta::CUENTA_GASTO
    ];
});
