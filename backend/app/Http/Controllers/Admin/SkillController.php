<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * List all skills.
     */
    public function index(): JsonResponse
    {
        $skills = Skill::orderBy('sort_order', 'asc')->get();

        return response()->json([
            'data' => SkillResource::collection($skills),
        ]);
    }

    /**
     * Create a new skill.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'level' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (Skill::max('sort_order') ?? 0) + 1;
        }

        $skill = Skill::create($validated);

        return response()->json([
            'message' => 'Skill created successfully.',
            'data' => new SkillResource($skill),
        ], 201);
    }

    /**
     * Update an existing skill.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'level' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $skill->update($validated);

        return response()->json([
            'message' => 'Skill updated successfully.',
            'data' => new SkillResource($skill),
        ]);
    }

    /**
     * Delete a skill.
     */
    public function destroy($id): JsonResponse
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json([
            'message' => 'Skill deleted successfully.'
        ]);
    }

    /**
     * Reorder skills.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:skills,id'],
            'orders.*.sort_order' => ['required', 'integer'],
        ]);

        foreach ($request->orders as $item) {
            Skill::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'message' => 'Skills reordered successfully.'
        ]);
    }
}
