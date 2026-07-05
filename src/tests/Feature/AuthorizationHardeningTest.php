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

/**
 * 2026-07 のセキュリティ監査で見つかった認可漏れ 4 件の回帰テスト。
 *   1. レビュー投稿は取引の当事者のみ・宛先は取引相手のみ
 *   2. メッセージは 画家 ⇄ 応募/オファーモデル のペアのみ
 *   3. closed/done の依頼には応募できない
 *   4. 募集人数(1名)を超える採用はできない
 */
class AuthorizationHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $painter;
    private User $model;
    private User $outsider; // 取引と無関係のモデル
    private Job $job;
    private JobApplication $application;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->painter = User::factory()->create(['role' => 'painter']);
        PainterProfile::create(['user_id' => $this->painter->id, 'display_name' => 'P']);

        $this->model = User::factory()->create(['role' => 'model']);
        ModelProfile::create(['user_id' => $this->model->id, 'display_name' => 'M', 'is_public' => true]);

        $this->outsider = User::factory()->create(['role' => 'model']);
        ModelProfile::create(['user_id' => $this->outsider->id, 'display_name' => 'O', 'is_public' => true]);

        $this->job = Job::create([
            'painter_id'    => $this->painter->id,
            'title'         => '監査テスト依頼',
            'description'   => '説明',
            'location_type' => 'offline',
            'status'        => 'done',
        ]);

        $this->application = JobApplication::create([
            'job_id'              => $this->job->id,
            'model_id'            => $this->model->id,
            'status'              => 'accepted',
            'payment_received_at' => now(),
        ]);
    }

    // ── 1. レビューの当事者チェック ──

    public function test_outsider_cannot_post_review(): void
    {
        $response = $this->actingAs($this->outsider)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 1,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('reviews', ['job_id' => $this->job->id]);
    }

    public function test_participant_cannot_review_arbitrary_target(): void
    {
        // 画家が「取引相手のモデル」以外（部外者）宛にレビューを書けない
        $response = $this->actingAs($this->painter)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->outsider->id,
            'rating' => 1,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('reviews', ['job_id' => $this->job->id]);
    }

    public function test_outsider_gets_403_not_validation_error(): void
    {
        // 認可が validation より先: 部外者は不正入力でも 422 でなく 403 で弾かれる
        $response = $this->actingAs($this->outsider)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 99, // 範囲外の不正値
        ]);

        $response->assertStatus(403);
        $response->assertSessionHasNoErrors();
    }

    public function test_participant_can_review_counterpart(): void
    {
        $response = $this->actingAs($this->painter)->post(route('reviews.store', $this->job), [
            'reviewed_user_id' => $this->model->id,
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'job_id'           => $this->job->id,
            'reviewer_id'      => $this->painter->id,
            'reviewed_user_id' => $this->model->id,
        ]);
    }

    // ── 2. メッセージの当事者チェック ──

    public function test_outsider_cannot_send_message(): void
    {
        $response = $this->actingAs($this->outsider)->post(route('messages.store', $this->job), [
            'body'        => '無関係ですが送ります',
            'receiver_id' => $this->painter->id,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('messages', ['job_id' => $this->job->id]);
    }

    public function test_painter_cannot_message_unrelated_user(): void
    {
        $response = $this->actingAs($this->painter)->post(route('messages.store', $this->job), [
            'body'        => '無関係のモデルへ',
            'receiver_id' => $this->outsider->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_participants_can_message_each_other(): void
    {
        $response = $this->actingAs($this->model)->post(route('messages.store', $this->job), [
            'body'        => 'よろしくお願いします',
            'receiver_id' => $this->painter->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'job_id'      => $this->job->id,
            'sender_id'   => $this->model->id,
            'receiver_id' => $this->painter->id,
        ]);
    }

    public function test_outsider_cannot_view_conversation(): void
    {
        $response = $this->actingAs($this->outsider)
            ->get(route('messages.show', ['job' => $this->job, 'with' => $this->painter->id]));

        $response->assertStatus(403);
    }

    // ── 3. closed 依頼への応募禁止 ──

    public function test_cannot_apply_to_closed_job(): void
    {
        $closedJob = Job::create([
            'painter_id'    => $this->painter->id,
            'title'         => '締切済み依頼',
            'description'   => '説明',
            'location_type' => 'offline',
            'status'        => 'closed',
        ]);

        $response = $this->actingAs($this->outsider)->post(route('model.jobs.apply', $closedJob), [
            'message' => '応募します',
        ]);

        $response->assertRedirect(route('jobs.show', $closedJob));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('job_applications', [
            'job_id'   => $closedJob->id,
            'model_id' => $this->outsider->id,
        ]);
    }

    // ── 4. 募集人数超過の採用禁止 ──

    public function test_cannot_accept_beyond_recruitment_limit(): void
    {
        $openJob = Job::create([
            'painter_id'         => $this->painter->id,
            'title'              => '1名募集',
            'description'        => '説明',
            'location_type'      => 'offline',
            'status'             => 'open',
            'recruitment_number' => 1,
        ]);

        JobApplication::create([
            'job_id'   => $openJob->id,
            'model_id' => $this->model->id,
            'status'   => 'accepted',
        ]);
        $second = JobApplication::create([
            'job_id'   => $openJob->id,
            'model_id' => $this->outsider->id,
            'status'   => 'pending',
        ]);

        $response = $this->actingAs($this->painter)
            ->post(route('painter.jobs.applications.accept', [$openJob, $second]));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('job_applications', [
            'id'     => $second->id,
            'status' => 'pending', // 採用されていないこと
        ]);
    }
}
