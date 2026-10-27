<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting || $setting->value === null) {
            return $default;
        }

        // Check if value is JSON
        $decoded = json_decode($setting->value, true);
        if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
            return $decoded;
        }

        return $setting->value;
    }

    public static function setValue(string $key, $value, string $group = 'general'): self
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public static function getAllMapped(): array
    {
        $settings = static::all();
        $map = [];
        foreach ($settings as $setting) {
            $val = $setting->value;
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                $val = $decoded;
            }
            $map[$setting->key] = $val;
        }

        // Add computed asset URLs
        if (!empty($map['hero_avatar']) && !str_starts_with($map['hero_avatar'], 'http')) {
            $map['hero_avatar_url'] = url(Storage::url($map['hero_avatar']));
        } else {
            $map['hero_avatar_url'] = $map['hero_avatar'] ?? null;
        }

        if (!empty($map['cv_file_path']) && !str_starts_with($map['cv_file_path'], 'http')) {
            $map['cv_file_url'] = url(Storage::url($map['cv_file_path']));
        } else {
            $map['cv_file_url'] = $map['cv_file_path'] ?? null;
        }

        if (!empty($map['site_logo']) && !str_starts_with($map['site_logo'], 'http')) {
            $map['site_logo_url'] = url(Storage::url($map['site_logo']));
        } else {
            $map['site_logo_url'] = $map['site_logo'] ?? null;
        }

        return $map;
    }
}
