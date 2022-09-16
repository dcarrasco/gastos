<?php

namespace Database\Factories\Gastos;

use App\Models\Gastos\TipoGasto;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoGastoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TipoGasto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_gasto' => $this->faker->words(3, true),
        ];
    }
}
