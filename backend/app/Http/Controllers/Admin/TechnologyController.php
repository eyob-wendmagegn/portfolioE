<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TechnologyResource;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechnologyController extends Controller
{
    public function index(): JsonResponse
    {
        $tech = Technology::orderBy('name', 'asc')->get();
        return response()->json(['data' => TechnologyResource::collection($tech)]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:technologies,name'],
            'slug' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $item = Technology::create($validated);
        return response()->json([
            'message' => 'Technology created successfully.',
            'data' => new TechnologyResource($item),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $item = Technology::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:technologies,name,' . $item->id],
            'slug' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $item->update($validated);
        return response()->json([
            'message' => 'Technology updated successfully.',
            'data' => new TechnologyResource($item),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $item = Technology::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Technology deleted successfully.']);
    }
}
