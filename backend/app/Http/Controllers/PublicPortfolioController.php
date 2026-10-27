<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\LibraryDashboardResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SkillResource;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\LibraryDashboardImage;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicPortfolioController extends Controller
{
    /**
     * Get aggregated portfolio payload for fast unified load.
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::getAllMapped();

        $categories = Category::orderBy('sort_order', 'asc')->get();

        $featuredProjects = Project::published()
            ->featured()
            ->with(['category', 'technologies', 'images'])
            ->orderBy('sort_order', 'asc')
            ->get();

        $allProjects = Project::published()
            ->with(['category', 'technologies', 'images'])
            ->orderBy('sort_order', 'asc')
            ->get();

        $libraryDashboards = LibraryDashboardImage::visible()
            ->orderBy('sort_order', 'asc')
            ->get();

        $skills = Skill::visible()
            ->orderBy('sort_order', 'asc')
            ->get();

        $services = Service::active()
            ->orderBy('sort_order', 'asc')
            ->get();

        $education = Education::orderBy('sort_order', 'asc')->get();

        $stats = [
            'total_projects' => Project::published()->count(),
            'technologies_count' => Technology::count(),
            'completed_projects' => Project::published()->count(),
            'total_skills' => Skill::visible()->count(),
        ];

        return response()->json([
            'settings' => $settings,
            'categories' => CategoryResource::collection($categories),
            'featured_projects' => ProjectResource::collection($featuredProjects),
            'projects' => ProjectResource::collection($allProjects),
            'library_dashboards' => LibraryDashboardResource::collection($libraryDashboards),
            'skills' => SkillResource::collection($skills),
            'services' => ServiceResource::collection($services),
            'education' => EducationResource::collection($education),
            'stats' => $stats,
        ]);
    }

    /**
     * Filter projects by category and search keyword.
     */
    public function projects(Request $request): JsonResponse
    {
        $query = Project::published()
            ->with(['category', 'technologies', 'images'])
            ->orderBy('sort_order', 'asc');

        if ($request->filled('category') && $request->category !== 'all') {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($request->filled('search')) {
            $search = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(short_description) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$search])
                  ->orWhereHas('technologies', function ($tQ) use ($search) {
                      $tQ->whereRaw('LOWER(name) LIKE ?', [$search]);
                  });
            });
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        $projects = $query->get();

        return response()->json([
            'data' => ProjectResource::collection($projects),
        ]);
    }

    /**
     * Get single project by slug.
     */
    public function project(string $slug): JsonResponse
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->with(['category', 'technologies', 'images'])
            ->firstOrFail();

        return response()->json([
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Get public skills.
     */
    public function skills(): JsonResponse
    {
        $skills = Skill::visible()->orderBy('sort_order', 'asc')->get();
        return response()->json(['data' => SkillResource::collection($skills)]);
    }

    /**
     * Get active services.
     */
    public function services(): JsonResponse
    {
        $services = Service::active()->orderBy('sort_order', 'asc')->get();
        return response()->json(['data' => ServiceResource::collection($services)]);
    }

    /**
     * Get Library Management System dashboard cards.
     */
    public function libraryDashboards(): JsonResponse
    {
        $dashboards = LibraryDashboardImage::visible()->orderBy('sort_order', 'asc')->get();
        return response()->json(['data' => LibraryDashboardResource::collection($dashboards)]);
    }

    /**
     * Get public site settings.
     */
    public function siteSettings(): JsonResponse
    {
        return response()->json([
            'data' => SiteSetting::getAllMapped(),
        ]);
    }

    /**
     * Handle public contact message submission with rate limiting.
     */
    public function contact(ContactRequest $request): JsonResponse
    {
        $ip = $request->ip();
        $throttleKey = 'contact-submission:' . $ip;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'message' => "Too many messages sent. Please wait {$seconds} seconds before trying again.",
            ], 429);
        }

        RateLimiter::hit($throttleKey, 600); // 5 attempts per 10 minutes

        $validated = $request->validated();
        $validated['ip_address'] = $ip;

        $message = ContactMessage::create($validated);

        return response()->json([
            'message' => 'Thank you for reaching out! Your message has been received successfully. I will get back to you shortly.',
            'data' => [
                'id' => $message->id,
                'created_at' => $message->created_at->toISOString(),
            ],
        ], 201);
    }

    /**
     * Download CV / Resume file.
     */
    public function downloadCv(): BinaryFileResponse|JsonResponse
    {
        $cvPath = SiteSetting::getValue('cv_file_path');

        if (!$cvPath || !Storage::disk('public')->exists($cvPath)) {
            return response()->json([
                'message' => 'Resume file is currently unavailable or being updated. Please contact me directly.'
            ], 404);
        }

        $fullPath = Storage::disk('public')->path($cvPath);
        $fileName = 'Eyob_Wendmamagegn_CV.' . pathinfo($fullPath, PATHINFO_EXTENSION);

        return response()->download($fullPath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
