<?php

namespace Database\Factories\Acl;

use App\Models\Acl\App;
use App\Models\Acl\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rol::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'app_id' => App::factory(),
            'rol' => $this->faker->words(3, true),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
