<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->city,
            'slug'       => $this->faker->unique()->slug,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
