<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $painter;
    private User $model;
    private Job $job;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->painter = User::factory()->create(['role' => 'painter']);
        PainterProfile::create(['user_id' => $this->painter->id, 'display_name' => 'P']);

        $this->model = User::factory()->create(['role' => 'model']);
        ModelProfile::create(['user_id' => $this->model->id, 'display_name' => 'M', 'is_public' => true]);

        $this->job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => '完了済み案件',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'done',
        ]);

        JobApplication::create([
            'job_id' => $this->job->id,
            'model_id' => $this->model->id,
            'status' => 'accepted',
        ]);
    }

    public function test_painter_can_review_model(): void
    {
        $response = $this->actingAs($this->painter)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 'very_good',
            'comment' => '素晴らしいモデルでした。',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'job_id' => $this->job->id,
            'reviewer_id' => $this->painter->id,
            'reviewed_user_id' => $this->model->id,
            'rating' => 'very_good',
        ]);
    }

    public function test_model_can_review_painter(): void
    {
        $response = $this->actingAs($this->model)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->painter->id,
            'rating' => 'good',
            'comment' => '丁寧な進行でした。',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'reviewer_id' => $this->model->id,
            'reviewed_user_id' => $this->painter->id,
            'rating' => 'good',
        ]);
    }

    public function test_cannot_post_duplicate_review(): void
    {
        Review::create([
            'job_id' => $this->job->id,
            'reviewer_id' => $this->painter->id,
            'reviewed_user_id' => $this->model->id,
            'rating' => 'very_good',
        ]);

        $response = $this->actingAs($this->painter)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 'good',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Review::where('job_id', $this->job->id)->where('reviewer_id', $this->painter->id)->count());
    }

    public function test_review_rating_must_be_valid(): void
    {
        $response = $this->actingAs($this->painter)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 'invalid_rating',
        ]);
        $response->assertSessionHasErrors('rating');
    }

    public function test_review_comment_has_max_length(): void
    {
        $response = $this->actingAs($this->painter)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 'good',
            'comment' => str_repeat('あ', 2001),
        ]);
        $response->assertSessionHasErrors('comment');
    }

    public function test_unauthenticated_cannot_post_review(): void
    {
        $response = $this->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 'very_good',
        ]);
        $response->assertRedirect(route('login'));
    }
}
