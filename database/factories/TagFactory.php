<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Tag;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $label = $this->faker->unique()->word();
        return [
            'label' => $label,
            'slug' => Str::slug($label) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}