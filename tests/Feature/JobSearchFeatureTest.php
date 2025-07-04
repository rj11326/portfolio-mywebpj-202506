<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Job;
use App\Models\Company;
use App\Models\Tag;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class JobSearchFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $tag;
    protected $tag2;

    public function setUp(): void
    {
        parent::setUp();

        $company = Company::factory()->create();
        $category1 = JobCategory::factory()->create(['name' => '開発']);
        $category2 = JobCategory::factory()->create(['name' => 'テスト']);
        $location1 = Location::factory()->create(['name' => '東京']);
        $location2 = Location::factory()->create(['name' => '大阪']);

        $job1 = Job::factory()->create([
            'title' => 'PHPエンジニア',
            'description' => 'Laravel経験者歓迎',
            'company_id' => $company->id,
            'job_category_id' => $category1->id,
            'location_id' => $location1->id,
            'employment_type' => 1, // 正社員
            'salary_min' => 400,
            'is_active' => true,
            'application_deadline' => now()->addMonth(),
        ]);
        $job2 = Job::factory()->create([
            'title' => 'テストエンジニア',
            'description' => 'テスト経験者歓迎',
            'company_id' => $company->id,
            'job_category_id' => $category2->id,
            'location_id' => $location2->id,
            'employment_type' => 2, // 契約社員
            'salary_min' => 300,
            'is_active' => true,
            'application_deadline' => now()->addMonth(),
        ]);
        $this->tag = Tag::factory()->create(['label' => 'リモート']);
        $this->tag2 = Tag::factory()->create(['label' => '副業OK']);
        $job1->tags()->attach([$this->tag->id, $this->tag2->id]);
    }

    #[Test]
    public function api_キーワード検索ができる()
    {
        $response = $this->get('/api/jobs?q=PHP');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_タグ検索ができる()
    {
        $tag = Tag::where('label', 'リモート')->first();
        $response = $this->get('/api/jobs?tags=' . $tag->id);
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }
    
    #[Test]
    public function api_雇用形態検索ができる()
    {
        $response = $this->get('/api/jobs?employment_types=1');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_職種検索ができる()
    {
        $category1 = JobCategory::where('name', '開発')->first();
        $response = $this->get('/api/jobs?job_categories=' . $category1->id);
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_勤務地検索ができる()
    {
        $location1 = Location::where('name', '東京')->first();
        $response = $this->get('/api/jobs?locations=' . $location1->id);
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_給与フィルタができる()
    {
        $response = $this->get('/api/jobs?salary=400');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_アクティブな求人のみ取得できる()
    {
        $job = Job::where('title', 'PHPエンジニア')->first();
        $job->is_active = false;
        $job->save();

        $response = $this->get('/api/jobs');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(1, $data);
        $this->assertEquals('テストエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_該当しないキーワードで0件が返る()
    {
        $response = $this->get('/api/jobs?q=営業');
        $response->assertStatus(200)
            ->assertJsonCount(0, 'jobs');
        $data = $response->json('jobs');
        $this->assertCount(0, $data);
    }

    #[Test]
    public function api_応募締切過ぎは取得されない()
    {
        Job::factory()->create([
            'title' => '締切済み求人',
            'company_id' => Company::first()->id,
            'job_category_id' => JobCategory::first()->id,
            'location_id' => Location::first()->id,
            'salary_min' => 200,
            'salary_max' => 200,
            'is_active' => true,
            'application_deadline' => now()->subDay(),
        ]);
        $response = $this->get('/api/jobs');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $titles = array_column($data, 'title');
        $this->assertNotContains('締切済み求人', $titles);
    }

    #[Test]
    public function api_給与順ソートできる()
    {
        Job::where('title', 'PHPエンジニア')->first()->update(['salary_max' => 999]);
        Job::where('title', 'テストエンジニア')->first()->update(['salary_max' => 100]);
        $response = $this->get('/api/jobs?sort=salary');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertEquals('PHPエンジニア', $data[0]['title']);
    }

    #[Test]
    public function api_ページネーション動作確認()
    {
        // 既に2件あるので追加で6件
        Job::factory()->count(6)->create([
            'company_id' => Company::first()->id,
            'job_category_id' => JobCategory::first()->id,
            'location_id' => Location::first()->id,
            'salary_min' => 100,
            'salary_max' => 200,
            'is_active' => true,
            'application_deadline' => now()->addMonth(),
        ]);
        $response = $this->get('/api/jobs?page=2');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        // デフォルトperPage=6 → 2ページ目は2件
        $this->assertCount(2, $data);
    }

    #[Test]
    public function api_存在しないカテゴリ指定で0件()
    {
        $response = $this->get('/api/jobs?job_categories=9999');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(0, $data);
    }

    #[Test]
    public function api_存在しないタグ指定で0件()
    {
        $response = $this->get('/api/jobs?tags=9999');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(0, $data);
    }

    #[Test]
    public function api_存在しない勤務地指定で0件()
    {
        $response = $this->get('/api/jobs?locations=9999');
        $response->assertStatus(200);
        $data = $response->json('jobs');
        $this->assertCount(0, $data);
    }
}
