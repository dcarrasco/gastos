<?php

namespace Database\Factories\Gastos;

use App\Models\Gastos\TipoCuenta;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoCuentaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TipoCuenta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_cuenta' => $this->faker->words(3, true),
            'tipo' => TipoCuenta::CUENTA_GASTO,
        ];
    }
}
