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
