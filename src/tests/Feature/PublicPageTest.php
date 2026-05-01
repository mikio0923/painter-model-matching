<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_models_index_page_loads(): void
    {
        $this->get(route('models.index'))->assertStatus(200);
    }

    public function test_jobs_index_page_loads(): void
    {
        $this->get(route('jobs.index'))->assertStatus(200);
    }

    public function test_about_page_loads(): void
    {
        $this->get(route('about'))->assertStatus(200);
    }

    public function test_faq_page_loads(): void
    {
        $this->get(route('faq'))->assertStatus(200);
    }

    public function test_terms_page_loads(): void
    {
        $this->get(route('terms'))->assertStatus(200);
    }

    public function test_privacy_page_loads(): void
    {
        $this->get(route('privacy'))->assertStatus(200);
    }

    public function test_guideline_page_loads(): void
    {
        $this->get(route('guideline'))->assertStatus(200);
    }

    public function test_model_guide_page_loads(): void
    {
        $this->get(route('guide.model'))->assertStatus(200);
    }

    public function test_painter_guide_page_loads(): void
    {
        $this->get(route('guide.painter'))->assertStatus(200);
    }

    public function test_model_detail_page_loads(): void
    {
        $user = User::factory()->create(['role' => 'model']);
        $profile = ModelProfile::create([
            'user_id' => $user->id,
            'display_name' => 'テストモデル',
            'is_public' => true,
        ]);

        $this->get(route('models.show', $profile))->assertStatus(200);
    }

    public function test_job_detail_page_loads(): void
    {
        $painter = User::factory()->create(['role' => 'painter']);
        PainterProfile::create([
            'user_id' => $painter->id,
            'display_name' => 'テスト画家',
        ]);

        $job = Job::create([
            'painter_id' => $painter->id,
            'title' => 'テスト依頼',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        $this->get(route('jobs.show', $job))->assertStatus(200);
    }

    public function test_login_register_page_loads(): void
    {
        $this->get(route('login-register'))->assertStatus(200);
    }

    public function test_contact_page_loads(): void
    {
        $this->get(route('contact.create'))->assertStatus(200);
    }
}
