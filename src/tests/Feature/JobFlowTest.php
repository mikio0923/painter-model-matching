<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $painter;
    private User $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->painter = User::factory()->create(['role' => 'painter']);
        PainterProfile::create([
            'user_id' => $this->painter->id,
            'display_name' => $this->painter->name,
        ]);

        $this->model = User::factory()->create(['role' => 'model']);
        ModelProfile::create([
            'user_id' => $this->model->id,
            'display_name' => $this->model->name,
            'is_public' => true,
        ]);
    }

    public function test_painter_can_create_job(): void
    {
        $response = $this->actingAs($this->painter)->post(route('painter.jobs.store'), [
            'title' => 'テスト依頼',
            'description' => 'テスト依頼の説明文です。',
            'location_type' => 'offline',
            'prefecture' => '東京都',
        ]);

        $response->assertRedirect(route('painter.jobs.index'));
        $this->assertDatabaseHas('painter_jobs', [
            'painter_id' => $this->painter->id,
            'title' => 'テスト依頼',
            'status' => 'open',
        ]);
    }

    public function test_painter_can_delete_own_job(): void
    {
        $job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => '削除テスト',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->painter)->delete(route('painter.jobs.destroy', $job));

        $response->assertRedirect(route('painter.jobs.index'));
        $this->assertDatabaseMissing('painter_jobs', ['id' => $job->id]);
    }

    public function test_painter_cannot_delete_job_with_accepted_application(): void
    {
        $job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => '承認済み依頼',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'model_id' => $this->model->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($this->painter)->delete(route('painter.jobs.destroy', $job));

        $response->assertRedirect(route('painter.jobs.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('painter_jobs', ['id' => $job->id]);
    }

    public function test_model_can_apply_to_job(): void
    {
        $job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => '応募テスト依頼',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->model)->post(route('model.jobs.apply', $job), [
            'message' => '応募します。よろしくお願いします。',
        ]);

        $response->assertRedirect(route('model.applications.index'));
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'model_id' => $this->model->id,
            'status' => 'pending',
        ]);
    }

    public function test_model_cannot_apply_twice(): void
    {
        $job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => '重複応募テスト',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'model_id' => $this->model->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->model)->post(route('model.jobs.apply', $job));

        $response->assertSessionHas('error', '既に応募済みです');
    }

    public function test_other_painter_cannot_delete_job(): void
    {
        $otherPainter = User::factory()->create(['role' => 'painter']);

        $job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => '他人の依頼',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        $response = $this->actingAs($otherPainter)->delete(route('painter.jobs.destroy', $job));

        $response->assertStatus(403);
    }
}
