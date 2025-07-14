<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('ja_JP');
        $faker->unique(true);
        
        $this->call([
            AdminSeeder::class,
            JobCategorySeeder::class,
            TagSeeder::class,
            AreaLocationSeeder::class,
        ]);

        $jobCategoryIds = JobCategory::whereNotNull('parent_id')->pluck('id')->all();
        $locations = Location::all();

        // 会社を5件作成し、各会社にユーザーと求人を紐付け
        Company::factory(5)->create()->each(function ($company) use ($jobCategoryIds, $locations, $faker) {
            // 会社ごとにユーザーを1人作成
            CompanyUser::create([
                'company_id' => $company->id,
                'name'       => $faker->name(),
                'email'      => 'sample' . $company->id . '@example.com',
                'role'       =>  1, // 管理者
                'password'   => Hash::make('password'),
            ]);
        });

        $this->call([
            JobSeeder::class,
            JobTagSeeder::class,
            UserSeeder::class,
        ]);
    }
}