<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'description' => $this->description,
            'icon' => $this->icon,
            'action_text' => $this->action_text,
            'action_url' => $this->action_url,
            'sort_order' => (int) $this->sort_order,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
