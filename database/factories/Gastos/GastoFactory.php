<?php

namespace Database\Factories\Gastos;

use App\Models\Gastos\Gasto;
use Illuminate\Database\Eloquent\Factories\Factory;

class GastoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gasto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'anno' => now()->year,
            'mes' => now()->month,
            'fecha' => now(),
            'glosa' => $this->faker->words(5, true),
            'serie' => $this->faker->ean8(),
            'monto' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
