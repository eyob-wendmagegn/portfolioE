<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactMessageResource;
use App\Http\Resources\ProjectResource;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\LibraryDashboardImage;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Return live dashboard metrics and recent activities.
     */
    public function stats(): JsonResponse
    {
        $totalProjects = Project::count();
        $featuredProjects = Project::where('featured', true)->count();
        $publishedProjects = Project::where('status', 'published')->count();
        $draftProjects = Project::where('status', 'draft')->count();

        $totalSkills = Skill::count();
        $unreadMessages = ContactMessage::where('is_read', false)->whereNull('archived_at')->count();
        $totalMessages = ContactMessage::whereNull('archived_at')->count();
        $totalServices = Service::count();
        $totalCategories = Category::count();
        $uploadedDashboards = LibraryDashboardImage::whereNotNull('image_path')->count();

        $recentMessages = ContactMessage::whereNull('archived_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentProjects = Project::with(['category', 'technologies'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'stats' => [
                'total_projects' => $totalProjects,
                'featured_projects' => $featuredProjects,
                'published_projects' => $publishedProjects,
                'draft_projects' => $draftProjects,
                'total_skills' => $totalSkills,
                'unread_messages' => $unreadMessages,
                'total_messages' => $totalMessages,
                'total_services' => $totalServices,
                'total_categories' => $totalCategories,
                'uploaded_dashboards' => $uploadedDashboards,
            ],
            'recent_messages' => ContactMessageResource::collection($recentMessages),
            'recent_projects' => ProjectResource::collection($recentProjects),
        ]);
    }
}
