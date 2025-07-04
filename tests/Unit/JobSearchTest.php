<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Job;
use App\Models\Company;
use App\Models\Tag;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class JobSearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $company = Company::factory()->create();
        $category1 = JobCategory::factory()->create();
        $category2 = JobCategory::factory()->create();
        $location1 = Location::factory()->create();
        $location2 = Location::factory()->create();

        $job1 = Job::factory()->create([
            'title' => 'PHPエンジニア',
            'description' => 'Laravel経験者歓迎',
            'company_id' => $company->id,
            'job_category_id' => $category1->id,
            'location_id' => $location1->id,
            'employment_type' => 1, // 正社員
            'salary_min' => 400,
            'is_active' => true,
        ]);
        $job2 = Job::factory()->create([
            'title' => 'Javaエンジニア',
            'description' => 'TypeScript経験者歓迎',
            'company_id' => $company->id,
            'job_category_id' => $category2->id,
            'location_id' => $location2->id,
            'employment_type' => 2, // 契約社員
            'salary_min' => 300,
            'is_active' => true,
        ]);
        $tag = Tag::factory()->create(['label' => 'リモート']);
        $job1->tags()->attach($tag);
    }

    #[Test]
    public function キーワード検索ができる()
    {
        $jobs = Job::where('title', 'like', '%PHP%')->get();
        $this->assertCount(1, $jobs);
        $this->assertEquals('PHPエンジニア', $jobs->first()->title);
    }
    
    #[Test]
    public function タグ検索ができる()
    {
        $jobs = Job::whereHas('tags', function ($q) {
            $q->where('label', 'リモート');
        })->get();

        $this->assertCount(1, $jobs);
        $this->assertEquals('PHPエンジニア', $jobs->first()->title);
    }

    #[Test]
    public function 雇用形態検索ができる()
    {
        $jobs = Job::where('employment_type', 1)->get();
        $this->assertCount(1, $jobs);
        $this->assertEquals('PHPエンジニア', $jobs->first()->title);
    }

    #[Test]
    public function 職種検索ができる()
    {
        $jobs = Job::where('job_category_id', 1)->get();
        $this->assertCount(1, $jobs);
        $this->assertEquals('PHPエンジニア', $jobs->first()->title);
    }

    #[Test]
    public function 勤務地検索ができる()
    {
        $jobs = Job::where('location_id', 1)->get();
        $this->assertCount(1, $jobs);
        $this->assertEquals('PHPエンジニア', $jobs->first()->title);
    }

    #[Test]
    public function 給与フィルタができる()
    {
        $jobs = Job::where('salary_min', '>=', 400)->get();
        $this->assertCount(1, $jobs);
        $this->assertEquals('PHPエンジニア', $jobs->first()->title);
    }

    #[Test]
    public function アクティブな求人のみ取得できる()
    {
        Job::where('title', 'PHPエンジニア')->first()->update(['is_active' => false]);
        $jobs = Job::where('is_active', true)->get();
        $this->assertCount(1, $jobs);
        $this->assertEquals('Javaエンジニア', $jobs->first()->title);
    }

    #[Test]
    public function 該当しないキーワードで0件が返る()
    {
        $jobs = Job::where('title', 'like', '%営業%')->get();
        $this->assertCount(0, $jobs);
    }
}
