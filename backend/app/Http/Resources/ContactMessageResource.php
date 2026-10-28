<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'project_type' => $this->project_type,
            'message' => $this->message,
            'is_read' => (bool) $this->is_read,
            'replied_at' => $this->replied_at?->toISOString(),
            'reply_message' => $this->reply_message,
            'ip_address' => $this->ip_address,
            'archived_at' => $this->archived_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
