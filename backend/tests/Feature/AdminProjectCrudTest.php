<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class AdminProjectCrudTest extends TestCase
{
    private function getAdminToken(): string
    {
        $user = User::where('email', 'eyobnigata18@gmail.com')->first();
        return $user->createToken('test_token')->plainTextToken;
    }

    public function test_admin_can_create_new_project(): void
    {
        $token = $this->getAdminToken();
        $category = Category::first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/admin/projects', [
                'title' => 'Automated Test Project',
                'short_description' => 'A test project created by automated tests.',
                'description' => 'Detailed test project description.',
                'category_id' => $category?->id,
                'featured' => true,
                'status' => 'published',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Automated Test Project');

        $this->assertDatabaseHas('projects', [
            'title' => 'Automated Test Project',
            'featured' => true,
        ]);
    }

    public function test_admin_can_update_project(): void
    {
        $token = $this->getAdminToken();
        $project = Project::where('slug', 'automated-test-project')->first();
        if (!$project) {
            $project = Project::first();
        }

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/v1/admin/projects/' . $project->id, [
                'title' => 'Updated Project Title',
                'short_description' => 'Updated short description.',
                'featured' => false,
                'status' => 'published',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Project Title');
    }

    public function test_admin_can_delete_project(): void
    {
        $token = $this->getAdminToken();
        $project = Project::where('title', 'Updated Project Title')->first();
        if (!$project) {
            $project = Project::create([
                'title' => 'Temporary Delete Test',
                'slug' => 'temporary-delete-test',
                'status' => 'published',
            ]);
        }

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/v1/admin/projects/' . $project->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
