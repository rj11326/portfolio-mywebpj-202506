<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['label' => 'PHP', 'slug' => 'php'],
            ['label' => 'Laravel', 'slug' => 'laravel'],
            ['label' => 'JavaScript', 'slug' => 'javascript'],
            ['label' => 'リモート', 'slug' => 'remote'],
            ['label' => '未経験歓迎', 'slug' => 'beginner-friendly'],
            ['label' => 'フルリモート', 'slug' => 'full-remote'],
            ['label' => '正社員', 'slug' => 'full-time'],
            ['label' => '契約社員', 'slug' => 'contract'],
            ['label' => 'フロントエンド', 'slug' => 'frontend'],
            ['label' => 'バックエンド', 'slug' => 'backend'],
            ['label' => 'UI/UX', 'slug' => 'ui-ux'],
            ['label' => 'データベース', 'slug' => 'database'],
        ];
        foreach ($tags as $t) {
            Tag::create($t);
        }
    }
}