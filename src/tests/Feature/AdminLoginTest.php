<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_loads(): void
    {
        $this->get(route('admin.login'))->assertStatus(200);
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_login_rejects_wrong_password(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_login_rejects_non_admin_user(): void
    {
        // model ロールのユーザーが admin ログインを試みる → 失敗
        User::factory()->create([
            'role' => 'model',
            'email' => 'model@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'model@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_login_rejects_painter_user(): void
    {
        User::factory()->create([
            'role' => 'painter',
            'email' => 'painter@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'painter@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unauthenticated_admin_url_redirects_to_admin_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_unauthenticated_general_url_redirects_to_general_login(): void
    {
        // mypage は通常の auth が必要
        $response = $this->get(route('mypage'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_admin_login_validates_required_fields(): void
    {
        $response = $this->post(route('admin.login'), []);
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
