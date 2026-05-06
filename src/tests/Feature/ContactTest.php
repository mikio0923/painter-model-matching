<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_is_accessible_to_guests(): void
    {
        $this->get(route('contact.create'))->assertStatus(200);
    }

    public function test_guest_can_submit_contact(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '山田太郎',
            'email' => 'guest@example.com',
            'subject' => 'サービスについて',
            'message' => 'はじめまして。サービスの詳細を伺いたいです。',
        ]);

        $response->assertRedirect(route('contact.create'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contacts', [
            'name' => '山田太郎',
            'email' => 'guest@example.com',
            'subject' => 'サービスについて',
            'user_id' => null,
        ]);
    }

    public function test_logged_in_user_contact_links_user_id(): void
    {
        $user = User::factory()->create(['role' => 'model']);

        $this->actingAs($user)->post(route('contact.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'プロフィール画像が表示されません',
            'message' => '更新後にトップページで反映されません。',
        ]);

        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'subject' => 'プロフィール画像が表示されません',
        ]);
    }

    public function test_contact_validation_rejects_missing_fields(): void
    {
        $response = $this->post(route('contact.store'), []);
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_contact_validation_rejects_invalid_email(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'X',
            'email' => 'not-an-email',
            'subject' => 'a',
            'message' => 'a',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_contact_message_max_length(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'X',
            'email' => 'x@example.com',
            'subject' => 's',
            'message' => str_repeat('あ', 5001),
        ]);
        $response->assertSessionHasErrors('message');
    }
}
