<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LibraryDashboardResource;
use App\Models\LibraryDashboardImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryDashboardController extends Controller
{
    /**
     * List all four library dashboard entries.
     */
    public function index(): JsonResponse
    {
        $dashboards = LibraryDashboardImage::orderBy('sort_order', 'asc')->get();

        return response()->json([
            'data' => LibraryDashboardResource::collection($dashboards),
        ]);
    }

    /**
     * Update dashboard details and/or upload/replace screenshot.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $dashboard = LibraryDashboardImage::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'badge_color' => ['nullable', 'string', 'max:50'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'features' => ['nullable'],
            'sort_order' => ['nullable', 'integer'],
            'is_visible' => ['nullable', 'boolean'],
            'screenshot' => ['nullable', 'file', 'image', 'max:10240', 'mimes:jpeg,jpg,png,webp'],
        ]);

        $dashboard->title = $request->title;
        $dashboard->description = $request->description;

        if ($request->filled('badge_color')) {
            $dashboard->badge_color = $request->badge_color;
        }

        if ($request->filled('alt_text')) {
            $dashboard->alt_text = $request->alt_text;
        }

        if ($request->has('features')) {
            $features = $request->features;
            if (is_string($features)) {
                $decoded = json_decode($features, true);
                $features = json_last_error() === JSON_ERROR_NONE ? $decoded : array_filter(array_map('trim', explode("\n", $features)));
            }
            $dashboard->features = $features;
        }

        if ($request->has('sort_order')) {
            $dashboard->sort_order = (int) $request->sort_order;
        }

        if ($request->has('is_visible')) {
            $dashboard->is_visible = $request->boolean('is_visible');
        }

        // Handle screenshot upload
        if ($request->hasFile('screenshot')) {
            if ($dashboard->image_path && Storage::disk('public')->exists($dashboard->image_path)) {
                Storage::disk('public')->delete($dashboard->image_path);
            }
            $path = $request->file('screenshot')->store('library-dashboards', 'public');
            $dashboard->image_path = $path;
        }

        $dashboard->save();

        return response()->json([
            'message' => "Dashboard '{$dashboard->title}' updated successfully.",
            'data' => new LibraryDashboardResource($dashboard),
        ]);
    }

    /**
     * Remove screenshot image file and revert to placeholder.
     */
    public function removeImage($id): JsonResponse
    {
        $dashboard = LibraryDashboardImage::findOrFail($id);

        if ($dashboard->image_path && Storage::disk('public')->exists($dashboard->image_path)) {
            Storage::disk('public')->delete($dashboard->image_path);
        }

        $dashboard->image_path = null;
        $dashboard->save();

        return response()->json([
            'message' => "Screenshot removed for {$dashboard->title}. Dashboard reverted to placeholder mode.",
            'data' => new LibraryDashboardResource($dashboard),
        ]);
    }
}
