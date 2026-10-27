<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'image_path',
        'caption',
        'alt_text',
        'sort_order',
    ];

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->image_path)) {
                    return null;
                }
                if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
                    return $this->image_path;
                }
                return url(Storage::url($this->image_path));
            }
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
