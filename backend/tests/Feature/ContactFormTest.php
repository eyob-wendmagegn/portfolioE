<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function test_contact_form_saves_valid_submission(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Project Inquiry',
            'project_type' => 'Full-Stack Development',
            'message' => 'Hello Eyob, I would like to discuss a web application project with you.',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data' => ['id']]);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john.doe@example.com',
            'subject' => 'Project Inquiry',
        ]);
    }

    public function test_contact_form_fails_validation_with_missing_fields(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_admin_can_send_direct_reply_to_message(): void
    {
        $admin = \App\Models\User::first() ?? \App\Models\User::factory()->create(['is_admin' => true]);
        $message = \App\Models\ContactMessage::create([
            'name' => 'Alice Test',
            'email' => 'alice@example.com',
            'subject' => 'Hiring Project',
            'message' => 'Can you build my app?',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin, 'sanctum')->postJson("/api/v1/admin/messages/{$message->id}/reply", [
            'reply' => 'Yes, I would be happy to discuss details!',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.replied_at', fn ($val) => !is_null($val))
            ->assertJsonPath('data.reply_message', 'Yes, I would be happy to discuss details!')
            ->assertJsonPath('data.is_read', true);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'reply_message' => 'Yes, I would be happy to discuss details!',
            'is_read' => true,
        ]);
    }

    public function test_admin_can_send_test_email(): void
    {
        $admin = \App\Models\User::first() ?? \App\Models\User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/admin/settings/test-email', [
            'email' => 'eyobnigata18@gmail.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }
}
