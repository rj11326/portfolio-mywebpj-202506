<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTagSeeder extends Seeder
{
    public function run(): void
    {
        // 仮に job_id=1,2、tag_id=1,2,3,4,5 で割り当て
        DB::table('job_tag')->insert([
            ['job_id' => 1, 'tag_id' => 1], // Webエンジニア × PHP
            ['job_id' => 1, 'tag_id' => 2], // Webエンジニア × Laravel
            ['job_id' => 1, 'tag_id' => 4], // Webエンジニア × リモート
            ['job_id' => 2, 'tag_id' => 3], // フロントエンド × JavaScript
            ['job_id' => 2, 'tag_id' => 5], // フロントエンド × 未経験歓迎
        ]);
    }
}