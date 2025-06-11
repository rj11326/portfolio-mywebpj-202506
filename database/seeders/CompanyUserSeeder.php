<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Hash;

class CompanyUserSeeder extends Seeder
{
    public function run(): void
    {
        Company::factory(10)->create()->each(function ($company) {
            CompanyUser::create([
                'company_id' => $company->id,
                'name'       => fake()->name(),
                'email'      => fake()->unique()->safeEmail(),
                'password'   => Hash::make('password'),
            ]);
        });
    }
}