<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->city,
            'slug'       => $this->faker->unique()->slug,
            'area_id'    => Area::factory(),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
