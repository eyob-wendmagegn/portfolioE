<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'project_type',
        'message',
        'is_read',
        'ip_address',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false)->whereNull('archived_at');
    }
}
