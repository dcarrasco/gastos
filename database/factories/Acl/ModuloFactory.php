<?php

namespace Database\Factories\Acl;

use App\Models\Acl\App;
use App\Models\Acl\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModuloFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Modulo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'app_id' => App::factory(),
            'modulo' => $this->faker->words(3, true),
            'descripcion' => $this->faker->sentence(),
            'llave_modulo' => Str::random(10),
            'icono' => $this->faker->word,
            'url' => $this->faker->url,
            'orden' => $this->faker->randomDigit,
            'created_at' => now(),
        ];
    }
}
