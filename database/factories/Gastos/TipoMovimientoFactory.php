<?php

namespace Database\Factories\Gastos;

use App\Models\Gastos\TipoMovimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoMovimientoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TipoMovimiento::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_movimiento' => $this->faker->words(3, true),
            'signo' => -1,
            'orden' => 10,
        ];
    }
}
