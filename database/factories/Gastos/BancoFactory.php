<?php

namespace Database\Factories\Gastos;

use App\Models\Gastos\Banco;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BancoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Banco::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->company(),
        ];
    }
}
