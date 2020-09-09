<?php

namespace Database\Factories\Acl;

use App\Models\Acl\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Usuario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->name,
            'activo' => $this->faker->boolean(80) ? 1 : 0,
            'username' => $this->faker->firstNameMale,
            'password' => bcrypt('secret'),
            'email' => $this->faker->unique()->safeEmail,
            'fecha_login' => $this->faker->dateTime,
            'ip_login' => $this->faker->localIpv4,
            'agente_login' => '',
            'login_errors' => 0,
            'remember_token' => Str::random(10),
        ];
    }
}
