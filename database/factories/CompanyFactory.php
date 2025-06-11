<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CompanyFactory extends Factory
{
    protected $model = \App\Models\Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->safeEmail(),
            'website' => $this->faker->url(),
            'business' => $this->faker->word(),
            'description' => $this->faker->paragraph,
            'founded_at' => $this->faker->date(),
            'capital' => $this->faker->numberBetween(10000, 100000),
            'employee_count' => $this->faker->numberBetween(10, 500),
        ];
    }
}