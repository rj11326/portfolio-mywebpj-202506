<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'company_id'      => Company::factory(),
            'job_category_id' => JobCategory::factory(),
            'location_id'     => Location::factory(),
            'title'           => $this->faker->jobTitle(),
            'location'        => $this->faker->city(),
            'salary_min'      => $this->faker->numberBetween(300, 600),
            'salary_max'      => $this->faker->numberBetween(700, 1200),
            'employment_type' => $this->faker->numberBetween(1, 3),
            'description'     => $this->faker->realText(50),
            'requirements'    => $this->faker->realText(30),
            'welcome_skills'  => $this->faker->realText(30),
            'required_qualifications' => $this->faker->realText(30),
            'tools'           => $this->faker->word(),
            'selection_flow'  => $this->faker->realText(20),
            'required_documents' => $this->faker->realText(20),
            'interview_place' => $this->faker->city(),
            'benefits'        => $this->faker->realText(20),
            'work_time'       => $this->faker->time('H:i'),
            'holiday'         => '土日祝',
            'number_of_positions' => $this->faker->numberBetween(1, 5),
            'is_active'       => true,
            'is_featured'     => false,
            'is_closed'       => false,
            'application_deadline' => $this->faker->optional()->date(),
        ];
    }
}
