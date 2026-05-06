<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Information;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    // ─────────────────────────────────────────
    // Dashboard
    // ─────────────────────────────────────────

    public function test_admin_dashboard_loads(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    // ─────────────────────────────────────────
    // Users
    // ─────────────────────────────────────────

    public function test_admin_can_view_users_index(): void
    {
        User::factory()->count(3)->create(['role' => 'model']);

        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_search_users_by_keyword(): void
    {
        User::factory()->create(['role' => 'painter', 'name' => 'Searchable Name']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['keyword' => 'Searchable']));

        $response->assertStatus(200);
        $response->assertSee('Searchable Name');
    }

    public function test_admin_can_view_user_detail(): void
    {
        $user = User::factory()->create(['role' => 'model', 'name' => 'TargetUser']);

        $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user))
            ->assertStatus(200)
            ->assertSee('TargetUser');
    }

    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create(['role' => 'model']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $user));

        $response->assertRedirect();
        // SoftDeletes が有効なので削除フラグだけ立てる
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    // ─────────────────────────────────────────
    // Jobs
    // ─────────────────────────────────────────

    public function test_admin_can_view_jobs_index(): void
    {
        $painter = User::factory()->create(['role' => 'painter']);
        Job::create([
            'painter_id' => $painter->id,
            'title' => 'Admin View Job',
            'description' => '内容',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.jobs.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_delete_job(): void
    {
        $painter = User::factory()->create(['role' => 'painter']);
        $job = Job::create([
            'painter_id' => $painter->id,
            'title' => 'Job to delete',
            'description' => '内容',
            'location_type' => 'offline',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.jobs.destroy', $job));

        $response->assertRedirect();
        $this->assertDatabaseMissing('painter_jobs', ['id' => $job->id]);
    }

    // ─────────────────────────────────────────
    // Contacts
    // ─────────────────────────────────────────

    public function test_admin_can_view_contacts_index(): void
    {
        Contact::create([
            'name' => 'Q', 'email' => 'q@example.com',
            'subject' => 'お問い合わせ', 'message' => '内容',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.contacts.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_mark_contact_as_read(): void
    {
        $contact = Contact::create([
            'name' => 'X', 'email' => 'x@example.com',
            'subject' => 'subject', 'message' => 'msg',
            'status' => 'pending', 'is_read' => false,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.contacts.read', $contact))
            ->assertRedirect();

        $this->assertTrue((bool) $contact->fresh()->is_read);
    }

    public function test_admin_can_delete_contact(): void
    {
        $contact = Contact::create([
            'name' => 'X', 'email' => 'x@example.com',
            'subject' => 's', 'message' => 'm',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.contacts.destroy', $contact))
            ->assertRedirect();

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    // ─────────────────────────────────────────
    // Information (Announcements)
    // ─────────────────────────────────────────

    public function test_admin_can_create_information(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.information.store'), [
                'title' => 'お知らせのタイトル',
                'content' => 'お知らせの本文。',
                'type' => 'information',
                'is_published' => true,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('information', [
            'title' => 'お知らせのタイトル',
        ]);
    }

    public function test_admin_can_delete_information(): void
    {
        $info = Information::create([
            'title' => '削除対象',
            'content' => '本文',
            'type' => 'information',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.information.destroy', $info))
            ->assertRedirect();

        $this->assertDatabaseMissing('information', ['id' => $info->id]);
    }

    // ─────────────────────────────────────────
    // Authorization
    // ─────────────────────────────────────────

    public function test_painter_cannot_access_admin(): void
    {
        $painter = User::factory()->create(['role' => 'painter']);
        $this->actingAs($painter)
            ->get(route('admin.dashboard'))
            ->assertStatus(403);
    }

    public function test_model_cannot_access_admin(): void
    {
        $model = User::factory()->create(['role' => 'model']);
        $this->actingAs($model)
            ->get(route('admin.users.index'))
            ->assertStatus(403);
    }
}
