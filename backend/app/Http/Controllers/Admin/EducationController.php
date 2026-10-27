<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\EducationResource;
use App\Models\Education;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * List all education entries.
     */
    public function index(): JsonResponse
    {
        $education = Education::orderBy('sort_order', 'asc')->get();

        return response()->json([
            'data' => EducationResource::collection($education),
        ]);
    }

    /**
     * Store new education record.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'degree_or_title' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_year' => ['nullable', 'string', 'max:50'],
            'end_year' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (Education::max('sort_order') ?? 0) + 1;
        }

        $item = Education::create($validated);

        return response()->json([
            'message' => 'Education entry created successfully.',
            'data' => new EducationResource($item),
        ], 201);
    }

    /**
     * Update education record.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $item = Education::findOrFail($id);

        $validated = $request->validate([
            'degree_or_title' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_year' => ['nullable', 'string', 'max:50'],
            'end_year' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Education entry updated successfully.',
            'data' => new EducationResource($item),
        ]);
    }

    /**
     * Delete education record.
     */
    public function destroy($id): JsonResponse
    {
        $item = Education::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Education entry deleted successfully.'
        ]);
    }
}
