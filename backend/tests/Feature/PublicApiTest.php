<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    public function test_portfolio_bundle_endpoint_returns_success_and_expected_structure(): void
    {
        $response = $this->getJson('/api/v1/portfolio');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'settings',
                'categories',
                'featured_projects',
                'projects',
                'library_dashboards',
                'skills',
                'services',
                'education',
                'stats' => [
                    'total_projects',
                    'technologies_count',
                    'completed_projects',
                    'total_skills',
                ],
            ]);
    }

    public function test_projects_endpoint_filters_by_category(): void
    {
        $response = $this->getJson('/api/v1/projects?category=full-stack-applications');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_single_project_details_can_be_retrieved_by_slug(): void
    {
        $response = $this->getJson('/api/v1/projects/library-management-system');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'library-management-system')
            ->assertJsonPath('data.title', 'Library Management System');
    }

    public function test_library_dashboards_endpoint_returns_four_roles(): void
    {
        $response = $this->getJson('/api/v1/library-dashboards');

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data');
    }
}
