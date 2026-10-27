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
}
