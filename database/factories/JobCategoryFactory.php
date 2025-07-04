<?php

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobCategoryFactory extends Factory
{
    protected $model = JobCategory::class;

    public function definition(): array
    {
        return [
            'name'      => $this->faker->word . 'カテゴリ',
            'slug'      => $this->faker->unique()->slug,
            'parent_id' => null, 
        ];
    }

    public function child($parentId = null)
    {
        return $this->state([
            'parent_id' => $parentId ?? JobCategory::factory(),
        ]);
    }
}
