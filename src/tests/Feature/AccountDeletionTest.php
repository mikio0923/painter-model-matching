<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\User;
use App\Services\AccountDeletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletion_form_displays_for_authenticated_user(): void
    {
        $user = User::factory()->create(['role' => 'painter']);

        $this->actingAs($user)
            ->get(route('account.delete.show'))
            ->assertStatus(200);
    }

    public function test_user_can_delete_account_with_correct_password(): void
    {
        $user = User::factory()->create([
            'role' => 'painter',
            'password' => Hash::make('mypassword'),
        ]);

        $response = $this->actingAs($user)->delete(route('account.delete'), [
            'password' => 'mypassword',
            'confirm' => '1',
            'reason' => 'pause',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertGuest();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deletion_reason' => 'pause',
        ]);
    }

    public function test_user_cannot_delete_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'role' => 'painter',
            'password' => Hash::make('correct'),
        ]);

        $response = $this->actingAs($user)->delete(route('account.delete'), [
            'password' => 'wrong',
            'confirm' => '1',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_user_must_confirm_deletion(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('mypassword'),
        ]);

        $response = $this->actingAs($user)->delete(route('account.delete'), [
            'password' => 'mypassword',
        ]);

        $response->assertSessionHasErrors('confirm');
    }

    public function test_painter_with_active_accepted_job_cannot_delete(): void
    {
        $painter = User::factory()->create([
            'role' => 'painter',
            'password' => Hash::make('mypassword'),
        ]);
        PainterProfile::create(['user_id' => $painter->id, 'display_name' => 'P']);

        $model = User::factory()->create(['role' => 'model']);

        $job = Job::create([
            'painter_id' => $painter->id,
            'title' => '進行中',
            'description' => '説明',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'model_id' => $model->id,
            'status' => 'accepted',
        ]);

        $service = app(AccountDeletionService::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->validateCanDelete($painter);
    }

    public function test_anonymize_command_anonymizes_users_past_grace_period(): void
    {
        $user = User::factory()->create([
            'role' => 'model',
            'name' => '元の名前',
            'email' => 'original@example.com',
        ]);
        $user->update(['deletion_requested_at' => now()->subDays(35)]);
        $user->delete();
        $user->forceFill(['deleted_at' => now()->subDays(35)])->save();

        $this->artisan('users:anonymize-deleted')
            ->assertSuccessful();

        $anonymized = User::onlyTrashed()->find($user->id);
        $this->assertEquals('退会済みユーザー', $anonymized->name);
        $this->assertNotNull($anonymized->anonymized_at);
        $this->assertStringContainsString('@deleted.local', $anonymized->email);
    }

    public function test_anonymize_command_does_not_touch_users_within_grace_period(): void
    {
        $user = User::factory()->create([
            'role' => 'model',
            'name' => '元の名前',
        ]);
        $user->update(['deletion_requested_at' => now()->subDays(5)]);
        $user->delete();

        $this->artisan('users:anonymize-deleted');

        $stillThere = User::onlyTrashed()->find($user->id);
        $this->assertEquals('元の名前', $stillThere->name);
        $this->assertNull($stillThere->anonymized_at);
    }
}
