<?php

namespace Database\Factories\Gastos;

use App\Models\Gastos\Cuenta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CuentaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cuenta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cuenta' => $this->faker->words(3, true),
        ];
    }
}
