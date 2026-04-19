<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_painter_pages(): void
    {
        $response = $this->get(route('painter.jobs.index'));
        $response->assertRedirect(); // 未ログインはログイン画面へリダイレクト
    }

    public function test_guest_cannot_access_model_pages(): void
    {
        $response = $this->get(route('model.applications.index'));
        $response->assertRedirect(); // 未ログインはログイン画面へリダイレクト
    }

    public function test_model_cannot_access_painter_pages(): void
    {
        $model = User::factory()->create(['role' => 'model']);

        $response = $this->actingAs($model)->get(route('painter.jobs.index'));
        $response->assertStatus(403);
    }

    public function test_painter_cannot_access_model_pages(): void
    {
        $painter = User::factory()->create(['role' => 'painter']);

        $response = $this->actingAs($painter)->get(route('model.applications.index'));
        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_access_admin_pages(): void
    {
        $painter = User::factory()->create(['role' => 'painter']);

        $response = $this->actingAs($painter)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}
