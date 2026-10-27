<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'cover_image_url' => $this->cover_image_url,
            'live_url' => $this->live_url,
            'github_url' => $this->github_url,
            'featured' => (bool) $this->featured,
            'status' => $this->status,
            'sort_order' => (int) $this->sort_order,
            'project_date' => $this->project_date,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'image_path' => $img->image_path,
                        'image_url' => $img->image_url,
                        'caption' => $img->caption,
                        'alt_text' => $img->alt_text,
                        'sort_order' => $img->sort_order,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
