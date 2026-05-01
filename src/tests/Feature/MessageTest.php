<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MessageTest extends TestCase
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
        ModelProfile::create([
            'user_id' => $this->model->id,
            'display_name' => 'M',
            'is_public' => true,
        ]);

        $this->job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => 'メッセージテスト',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        // 承認済み応募を作成（メッセージ機能はマッチング後のみ可能）
        JobApplication::create([
            'job_id' => $this->job->id,
            'model_id' => $this->model->id,
            'status' => 'accepted',
        ]);
    }

    public function test_painter_can_send_message_to_model(): void
    {
        $response = $this->actingAs($this->painter)
            ->post(route('messages.store', $this->job), [
                'body' => 'こんにちは、よろしくお願いします',
                'receiver_id' => $this->model->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'job_id' => $this->job->id,
            'sender_id' => $this->painter->id,
            'receiver_id' => $this->model->id,
            'body' => 'こんにちは、よろしくお願いします',
        ]);
    }

    public function test_model_can_send_message_to_painter(): void
    {
        $response = $this->actingAs($this->model)
            ->post(route('messages.store', $this->job), [
                'body' => '了解しました',
                'receiver_id' => $this->painter->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->model->id,
            'receiver_id' => $this->painter->id,
        ]);
    }

    public function test_message_body_is_required(): void
    {
        $response = $this->actingAs($this->painter)
            ->post(route('messages.store', $this->job), [
                'receiver_id' => $this->model->id,
            ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_message_body_max_length(): void
    {
        $response = $this->actingAs($this->painter)
            ->post(route('messages.store', $this->job), [
                'body' => str_repeat('あ', 5001),
                'receiver_id' => $this->model->id,
            ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_messages_index_shows_threads(): void
    {
        Message::create([
            'job_id' => $this->job->id,
            'sender_id' => $this->painter->id,
            'receiver_id' => $this->model->id,
            'body' => 'こんにちは',
        ]);

        $this->actingAs($this->model)
            ->get(route('messages.index'))
            ->assertStatus(200)
            ->assertSee('メッセージテスト');
    }
}
