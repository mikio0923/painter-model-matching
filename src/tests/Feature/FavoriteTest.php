<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Job;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private User $painter;
    private User $model;
    private ModelProfile $modelProfile;
    private Job $job;

    protected function setUp(): void
    {
        parent::setUp();

        $this->painter = User::factory()->create(['role' => 'painter']);
        PainterProfile::create(['user_id' => $this->painter->id, 'display_name' => 'P']);

        $this->model = User::factory()->create(['role' => 'model']);
        $this->modelProfile = ModelProfile::create([
            'user_id' => $this->model->id,
            'display_name' => 'モデルM',
            'is_public' => true,
        ]);

        $this->job = Job::create([
            'painter_id' => $this->painter->id,
            'title' => 'テスト依頼',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);
    }

    public function test_painter_can_favorite_model(): void
    {
        $response = $this->actingAs($this->painter)
            ->post(route('favorites.store.model', $this->modelProfile));

        $response->assertRedirect();
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->painter->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $this->modelProfile->id,
        ]);
    }

    public function test_cannot_favorite_same_model_twice(): void
    {
        Favorite::create([
            'user_id' => $this->painter->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $this->modelProfile->id,
        ]);

        $response = $this->actingAs($this->painter)
            ->post(route('favorites.store.model', $this->modelProfile));

        $response->assertSessionHas('error');
        $this->assertEquals(1, Favorite::where([
            'user_id' => $this->painter->id,
            'favoritable_id' => $this->modelProfile->id,
        ])->count());
    }

    public function test_can_unfavorite_model(): void
    {
        Favorite::create([
            'user_id' => $this->painter->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $this->modelProfile->id,
        ]);

        $response = $this->actingAs($this->painter)
            ->delete(route('favorites.destroy.model', $this->modelProfile));

        $response->assertRedirect();
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->painter->id,
            'favoritable_id' => $this->modelProfile->id,
        ]);
    }

    public function test_model_can_favorite_job(): void
    {
        $response = $this->actingAs($this->model)
            ->post(route('favorites.store.job', $this->job));

        $response->assertRedirect();
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->model->id,
            'favoritable_type' => Job::class,
            'favoritable_id' => $this->job->id,
        ]);
    }

    public function test_toggle_endpoint_creates_favorite_when_absent(): void
    {
        $response = $this->actingAs($this->painter)
            ->postJson(route('favorites.toggle'), [
                'target_type' => 'model',
                'target_id' => $this->modelProfile->id,
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'favorited' => true, 'count' => 1]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->painter->id,
            'favoritable_id' => $this->modelProfile->id,
        ]);
    }

    public function test_toggle_endpoint_removes_favorite_when_present(): void
    {
        Favorite::create([
            'user_id' => $this->painter->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $this->modelProfile->id,
        ]);

        $response = $this->actingAs($this->painter)
            ->postJson(route('favorites.toggle'), [
                'target_type' => 'model',
                'target_id' => $this->modelProfile->id,
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'favorited' => false, 'count' => 0]);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->painter->id,
            'favoritable_id' => $this->modelProfile->id,
        ]);
    }

    public function test_toggle_endpoint_works_for_jobs(): void
    {
        $response = $this->actingAs($this->model)
            ->postJson(route('favorites.toggle'), [
                'target_type' => 'job',
                'target_id' => $this->job->id,
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true, 'favorited' => true]);
    }

    public function test_toggle_endpoint_validates_input(): void
    {
        $response = $this->actingAs($this->painter)
            ->postJson(route('favorites.toggle'), [
                'target_type' => 'invalid',
                'target_id' => 'abc',
            ]);

        $response->assertStatus(422);
    }

    public function test_toggle_endpoint_returns_404_for_missing_target(): void
    {
        $response = $this->actingAs($this->painter)
            ->postJson(route('favorites.toggle'), [
                'target_type' => 'model',
                'target_id' => 99999,
            ]);

        $response->assertStatus(404);
    }

    public function test_toggle_endpoint_requires_authentication(): void
    {
        $response = $this->postJson(route('favorites.toggle'), [
            'target_type' => 'model',
            'target_id' => $this->modelProfile->id,
        ]);

        $response->assertStatus(401);
    }

    public function test_favorites_index_shows_only_own_favorites(): void
    {
        $other = User::factory()->create(['role' => 'painter']);

        Favorite::create([
            'user_id' => $this->painter->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $this->modelProfile->id,
        ]);
        Favorite::create([
            'user_id' => $other->id,
            'favoritable_type' => Job::class,
            'favoritable_id' => $this->job->id,
        ]);

        $this->actingAs($this->painter)
            ->get(route('favorites.index'))
            ->assertStatus(200)
            ->assertSee('モデルM');
    }
}
