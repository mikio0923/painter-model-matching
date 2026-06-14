<?php

namespace Tests\Feature;

use App\Models\IdentityVerification;
use App\Models\ModelProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IdentityVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $model;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // 本人確認機能は一旦停止中（プライバシー方針変更のため）
        // 機能を復活させる場合は routes/web.php のコメントアウトを外し、このskipも解除する
        $this->markTestSkipped('本人確認機能は停止中（restored if re-enabled）');

        Storage::fake('local');

        $this->model = User::factory()->create(['role' => 'model']);
        ModelProfile::create([
            'user_id' => $this->model->id,
            'display_name' => 'モデル太郎',
        ]);

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_model_can_view_identity_verification_form(): void
    {
        $this->actingAs($this->model)
            ->get(route('model.identity-verification'))
            ->assertStatus(200);
    }

    public function test_model_can_submit_identity_verification(): void
    {
        $response = $this->actingAs($this->model)->post(route('model.identity-verification.store'), [
            'document_type' => 'drivers_license',
            'front_image' => UploadedFile::fake()->create('front.jpg', 100, 'image/jpeg'),
            'back_image' => UploadedFile::fake()->create('back.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('model.identity-verification'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('identity_verifications', [
            'user_id' => $this->model->id,
            'document_type' => 'drivers_license',
            'status' => 'pending',
        ]);

        $verification = IdentityVerification::where('user_id', $this->model->id)->first();
        Storage::disk('local')->assertExists($verification->front_image_path);
        Storage::disk('local')->assertExists($verification->back_image_path);
    }

    public function test_model_cannot_submit_while_pending(): void
    {
        IdentityVerification::create([
            'user_id' => $this->model->id,
            'document_type' => 'passport',
            'front_image_path' => 'identity/dummy.jpg',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->model)->post(route('model.identity-verification.store'), [
            'document_type' => 'drivers_license',
            'front_image' => UploadedFile::fake()->create('front.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, IdentityVerification::where('user_id', $this->model->id)->count());
    }

    public function test_admin_can_view_pending_list(): void
    {
        IdentityVerification::create([
            'user_id' => $this->model->id,
            'document_type' => 'drivers_license',
            'front_image_path' => 'identity/dummy.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.identity-verifications.index'))
            ->assertStatus(200)
            ->assertSee($this->model->name);
    }

    public function test_admin_can_approve_verification(): void
    {
        $verification = IdentityVerification::create([
            'user_id' => $this->model->id,
            'document_type' => 'drivers_license',
            'front_image_path' => 'identity/dummy.jpg',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.identity-verifications.approve', $verification)
        );

        $response->assertRedirect(route('admin.identity-verifications.index'));

        $verification->refresh();
        $this->assertEquals('approved', $verification->status);
        $this->assertEquals($this->admin->id, $verification->reviewed_by);

        $this->assertTrue((bool) $this->model->modelProfile->fresh()->identity_verified);
    }

    public function test_admin_can_reject_verification_with_reason(): void
    {
        $verification = IdentityVerification::create([
            'user_id' => $this->model->id,
            'document_type' => 'drivers_license',
            'front_image_path' => 'identity/dummy.jpg',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.identity-verifications.reject', $verification),
            ['rejection_reason' => '画像が不鮮明です']
        );

        $response->assertRedirect(route('admin.identity-verifications.index'));

        $verification->refresh();
        $this->assertEquals('rejected', $verification->status);
        $this->assertEquals('画像が不鮮明です', $verification->rejection_reason);
    }

    public function test_admin_must_provide_rejection_reason(): void
    {
        $verification = IdentityVerification::create([
            'user_id' => $this->model->id,
            'document_type' => 'drivers_license',
            'front_image_path' => 'identity/dummy.jpg',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.identity-verifications.reject', $verification),
            []
        );

        $response->assertSessionHasErrors('rejection_reason');
    }

    public function test_non_admin_cannot_access_admin_pages(): void
    {
        $this->actingAs($this->model)
            ->get(route('admin.identity-verifications.index'))
            ->assertStatus(403);
    }

    public function test_non_admin_cannot_view_document_images(): void
    {
        $verification = IdentityVerification::create([
            'user_id' => $this->model->id,
            'document_type' => 'drivers_license',
            'front_image_path' => 'identity/dummy.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($this->model)
            ->get(route('admin.identity-verifications.image', [$verification, 'front']))
            ->assertStatus(403);
    }
}
