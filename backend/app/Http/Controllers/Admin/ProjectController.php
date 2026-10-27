<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * List all projects for admin management.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::with(['category', 'technologies', 'images'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(short_description) LIKE ?', [$search]);
            });
        }

        $projects = $query->get();

        return response()->json([
            'data' => ProjectResource::collection($projects),
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Project::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $data['slug'] = $slug;
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('projects', 'public');
            $data['cover_image'] = $path;
        }

        if (!isset($data['sort_order'])) {
            $data['sort_order'] = (Project::max('sort_order') ?? 0) + 1;
        }

        DB::beginTransaction();
        try {
            $technologyIds = $request->input('technology_ids', []);
            unset($data['technology_ids']);

            $project = Project::create($data);

            if (!empty($technologyIds)) {
                $project->technologies()->sync($technologyIds);
            }

            DB::commit();

            $project->load(['category', 'technologies', 'images']);

            return response()->json([
                'message' => 'Project created successfully.',
                'data' => new ProjectResource($project),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($data['cover_image']) && Storage::disk('public')->exists($data['cover_image'])) {
                Storage::disk('public')->delete($data['cover_image']);
            }
            throw $e;
        }
    }

    /**
     * Display a specific project.
     */
    public function show($id): JsonResponse
    {
        $project = Project::with(['category', 'technologies', 'images'])->findOrFail($id);

        return response()->json([
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Update an existing project.
     */
    public function update(UpdateProjectRequest $request, $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $data = $request->validated();

        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $data['slug'] = $slug;
        }

        // Handle cover image upload or removal
        if ($request->hasFile('cover_image')) {
            if ($project->cover_image && Storage::disk('public')->exists($project->cover_image)) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $path = $request->file('cover_image')->store('projects', 'public');
            $data['cover_image'] = $path;
        } elseif ($request->has('remove_cover_image') && $request->boolean('remove_cover_image')) {
            if ($project->cover_image && Storage::disk('public')->exists($project->cover_image)) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $data['cover_image'] = null;
        } else {
            unset($data['cover_image']);
        }

        DB::beginTransaction();
        try {
            $technologyIds = $request->input('technology_ids');
            unset($data['technology_ids']);

            $project->update($data);

            if (is_array($technologyIds)) {
                $project->technologies()->sync($technologyIds);
            }

            DB::commit();

            $project->load(['category', 'technologies', 'images']);

            return response()->json([
                'message' => 'Project updated successfully.',
                'data' => new ProjectResource($project),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a project and clean up associated stored images.
     */
    public function destroy($id): JsonResponse
    {
        $project = Project::with('images')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete cover image
            if ($project->cover_image && Storage::disk('public')->exists($project->cover_image)) {
                Storage::disk('public')->delete($project->cover_image);
            }

            // Delete gallery images
            foreach ($project->images as $img) {
                if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }

            $project->delete();
            DB::commit();

            return response()->json([
                'message' => 'Project and associated assets deleted successfully.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Upload an additional gallery image for a project.
     */
    public function uploadImage(Request $request, $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'image' => ['required', 'file', 'image', 'max:5120', 'mimes:jpeg,jpg,png,webp'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('image')->store('projects/gallery', 'public');
        $sortOrder = ($project->images()->max('sort_order') ?? 0) + 1;

        $projectImage = $project->images()->create([
            'image_path' => $path,
            'caption' => $request->caption,
            'alt_text' => $request->alt_text ?? $project->title . ' gallery screenshot',
            'sort_order' => $sortOrder,
        ]);

        return response()->json([
            'message' => 'Gallery image uploaded successfully.',
            'data' => [
                'id' => $projectImage->id,
                'image_path' => $projectImage->image_path,
                'image_url' => $projectImage->image_url,
                'caption' => $projectImage->caption,
                'alt_text' => $projectImage->alt_text,
                'sort_order' => $projectImage->sort_order,
            ],
        ], 201);
    }

    /**
     * Delete a specific gallery image.
     */
    public function deleteImage($imageId): JsonResponse
    {
        $image = ProjectImage::findOrFail($imageId);

        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return response()->json([
            'message' => 'Gallery image deleted successfully.'
        ]);
    }

    /**
     * Reorder projects.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:projects,id'],
            'orders.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($request->orders as $item) {
            Project::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'message' => 'Projects reordered successfully.'
        ]);
    }
}
