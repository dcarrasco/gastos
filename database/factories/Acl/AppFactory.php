<?php

namespace Database\Factories\Acl;

use App\Models\Acl\App;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = App::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'app' => $this->faker->words(3, true),
            'descripcion' => $this->faker->sentence(),
            'url' => $this->faker->url,
            'icono' => $this->faker->word,
            'orden' => $this->faker->randomDigit,
        ];
    }
}
