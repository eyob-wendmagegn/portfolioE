<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * List all services.
     */
    public function index(): JsonResponse
    {
        $services = Service::orderBy('sort_order', 'asc')->get();

        return response()->json([
            'data' => ServiceResource::collection($services),
        ]);
    }

    /**
     * Create a new service.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'action_text' => ['nullable', 'string', 'max:100'],
            'action_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (Service::max('sort_order') ?? 0) + 1;
        }

        $service = Service::create($validated);

        return response()->json([
            'message' => 'Service created successfully.',
            'data' => new ServiceResource($service),
        ], 201);
    }

    /**
     * Update an existing service.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'action_text' => ['nullable', 'string', 'max:100'],
            'action_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $service->update($validated);

        return response()->json([
            'message' => 'Service updated successfully.',
            'data' => new ServiceResource($service),
        ]);
    }

    /**
     * Delete a service.
     */
    public function destroy($id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully.'
        ]);
    }
}
