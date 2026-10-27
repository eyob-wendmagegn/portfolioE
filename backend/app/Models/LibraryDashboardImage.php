<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LibraryDashboardImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'title',
        'description',
        'image_path',
        'badge_color',
        'alt_text',
        'features',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

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

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
