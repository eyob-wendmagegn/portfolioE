<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'cover_image',
        'category_id',
        'live_url',
        'github_url',
        'featured',
        'status',
        'sort_order',
        'project_date',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected $appends = ['cover_image_url'];

    protected function coverImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->cover_image)) {
                    return null;
                }
                if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                    return $this->cover_image;
                }
                return url(Storage::url($this->cover_image));
            }
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order', 'asc');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }
}
