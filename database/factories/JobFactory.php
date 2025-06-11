<?php
namespace Database\Factories;

use App\Models\JobCategory;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition(): array
    {
        $categoryId = JobCategory::whereNotNull('parent_id')->inRandomOrder()->first()?->id;

        return [
            'company_id'      => Company::inRandomOrder()->first()?->id,
            'job_category_id' => $categoryId,
            'title'           => $this->faker->jobTitle,
            'location'        => $this->faker->city,
            'salary'          => $this->faker->numberBetween(3000000, 10000000),
            'employment_type' => $this->faker->randomElement(['正社員', '契約社員', '業務委託']),
            'description'     => $this->faker->paragraphs(3, true),
        ];
    }
}
