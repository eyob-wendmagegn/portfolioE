<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibraryDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'title' => $this->title,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'image_url' => $this->image_url,
            'badge_color' => $this->badge_color,
            'alt_text' => $this->alt_text,
            'features' => $this->features ?? [],
            'sort_order' => (int) $this->sort_order,
            'is_visible' => (bool) $this->is_visible,
        ];
    }
}
