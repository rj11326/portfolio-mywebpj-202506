<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Job;
use App\Models\Company;
use App\Models\Tag;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Test;

class JobSearchBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $job;
    protected $parent_category;
    protected $category1;
    protected $category2;
    protected $location1;
    protected $location2;
    protected $tag1;
    protected $tag2;
    

    public function setUp(): void
    {
        parent::setUp();

        $company = Company::factory()->create(['name' => 'テスト株式会社']);
        $this->parent_category = JobCategory::factory()->create(['name' => 'エンジニア']);
        $this->category1 = JobCategory::factory()->create([
            'name' => '開発',
            'parent_id' => $this->parent_category->id,
        ]);
        $this->category2 = JobCategory::factory()->create([
            'name' => 'テスト',
            'parent_id' => $this->parent_category->id,
        ]);
        $this->location1 = Location::factory()->create(['name' => '東京']);
        $this->location2 = Location::factory()->create(['name' => '大阪']);
        $this->tag1 = Tag::factory()->create(['label' => 'リモート']);
        $this->tag2 = Tag::factory()->create(['label' => '副業OK']);

        $this->job = Job::factory()->create([
            'title' => 'PHPエンジニア',
            'description' => 'Laravel経験者歓迎',
            'company_id' => $company->id,
            'job_category_id' => $this->category1->id,
            'location_id' => $this->location1->id,
            'employment_type' => 1,
            'salary_min' => 400,
            'salary_max' => 600,
            'is_active' => true,
            'application_deadline' => now()->addMonth(),
        ]);
        $this->job->tags()->attach([$this->tag1->id, $this->tag2->id]);

        Job::factory()->create([
            'title' => 'テストエンジニア',
            'description' => 'テスト経験者歓迎',
            'company_id' => $company->id,
            'job_category_id' => $this->category2->id,
            'location_id' => $this->location2->id,
            'employment_type' => 2,
            'salary_min' => 300,
            'salary_max' => 500,
            'is_active' => true,
            'application_deadline' => now()->addMonth(),
        ]);
    }

    #[Test]
    public function 求人一覧が表示される()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('#job-list', seconds: 10)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア')
                ->assertSee('テスト株式会社')
                ->assertSee('リモート');
        });
    }

    #[Test]
    public function キーワード検索が動作する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('input[type="text"]', 2)
                ->type('input[type="text"]', 'PHP')
                ->keys('input[type="text"]', '{enter}')
                ->pause(800)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア')
                ->assertDontSee('テストエンジニア');
        });
    }

    #[Test]
    public function タグ検索が動作する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('#job-list', 2)
                ->waitFor('[data-test="tag-' . $this->tag1->id .'"]', 2)
                ->click('[data-test="tag-' . $this->tag1->id .'"]')
                ->pause(800)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア');
        });
    }

    #[Test]
    public function 雇用形態フィルタが動作する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('#job-list', 2)
                ->waitFor('[data-test="employment-type-1"]', 2)
                ->click('[data-test="employment-type-1"]')
                ->pause(800)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア');
        });
    }

    #[Test]
    public function 職種フィルタが動作する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('#job-list', 2)
                ->pause(800)
                ->waitFor('[data-test="job-category-button"]', 20)
                ->press('[data-test="job-category-button"]')
                ->waitFor('[data-test="job-category-parent-' . $this->parent_category->id . '"]', 20)
                ->click('[data-test="job-category-parent-' . $this->parent_category->id . '"]')
                ->waitFor('[data-test="job-category-child-' . $this->category1->id . '"]', 2)
                ->click('[data-test="job-category-child-' . $this->category1->id . '"]')
                ->waitFor('[data-test="job-category-confirm-button"]', 2)
                ->click('[data-test="job-category-confirm-button"]')
                ->pause(800)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア');
        });
    }

    #[Test]
    public function 勤務地フィルタが動作する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('#job-list', 2)
                ->pause(800)
                ->waitFor('[data-test="location-button"]', 20)
                ->press('[data-test="location-button"]')
                ->waitFor('[data-test="area-' . $this->location1->area_id . '"]', 20)
                ->click('[data-test="area-' . $this->location1->area_id . '"]')
                ->waitFor('[data-test="location-' . $this->location1->id . '"]', 2)
                ->click('[data-test="location-' . $this->location1->id . '"]')
                ->waitFor('[data-test="location-confirm-button"]', 2)
                ->click('[data-test="location-confirm-button"]')
                ->pause(800)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア');
        });
    }

    #[Test]
    public function 給与フィルタが動作する()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->pause(800)
                ->script('
                var el = document.querySelector(\'input[type="range"]\');
                el.value = 400;
                el.dispatchEvent(new Event("input", { bubbles: true }));
                el.dispatchEvent(new Event("blur", { bubbles: true }));
            ');
            $browser->pause(800)
                ->waitForText('PHPエンジニア', 2)
                ->assertSee('PHPエンジニア');
        });
    }

    #[Test]
    public function 詳細ページへ遷移できる()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/jobs')
                ->waitFor('a[href="/jobs/' . $this->job->id . '"]', 10)
                ->click('a[href="/jobs/' . $this->job->id . '"]')
                ->waitForText('Laravel経験者歓迎', 5)
                ->assertPathIs('/jobs/' . $this->job->id)
                ->screenshot('detail-page-after-click');
        });
    }

}
