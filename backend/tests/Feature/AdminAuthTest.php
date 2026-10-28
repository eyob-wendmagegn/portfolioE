<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::updateOrCreate(
            ['email' => 'eyobnigata18@gmail.com'],
            [
                'name' => 'Eyob Wendmamagegn',
                'password' => Hash::make('Password123!'),
                'is_admin' => true,
            ]
        );
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'eyobnigata18@gmail.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'is_admin'],
            ])
            ->assertJsonPath('user.email', 'eyobnigata18@gmail.com')
            ->assertJsonPath('user.is_admin', true);
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'eyobnigata18@gmail.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_unauthenticated_request_to_admin_route_is_rejected(): void
    {
        $response = $this->getJson('/api/v1/admin/dashboard/stats');

        $response->assertStatus(401);
    }

    public function test_authenticated_admin_can_access_dashboard_stats(): void
    {
        $user = User::where('email', 'eyobnigata18@gmail.com')->first();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/admin/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => [
                    'total_projects',
                    'featured_projects',
                    'published_projects',
                    'draft_projects',
                    'total_skills',
                    'unread_messages',
                ],
            ]);
    }
}
