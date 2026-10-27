<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Get all settings.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => SiteSetting::getAllMapped(),
        ]);
    }

    /**
     * Update settings in bulk.
     */
    public function update(Request $request): JsonResponse
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            SiteSetting::setValue($key, $value);
        }

        return response()->json([
            'message' => 'Settings updated successfully.',
            'data' => SiteSetting::getAllMapped(),
        ]);
    }

    /**
     * Upload / replace profile image (hero avatar).
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'file', 'image', 'max:5120', 'mimes:jpeg,jpg,png,webp'],
        ]);

        $current = SiteSetting::getValue('hero_avatar');
        if ($current && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        SiteSetting::setValue('hero_avatar', $path, 'hero');

        return response()->json([
            'message' => 'Profile picture uploaded successfully.',
            'hero_avatar' => $path,
            'hero_avatar_url' => url(Storage::url($path)),
        ]);
    }

    /**
     * Remove custom profile avatar and revert to placeholder.
     */
    public function removeAvatar(): JsonResponse
    {
        $current = SiteSetting::getValue('hero_avatar');
        if ($current && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }

        SiteSetting::setValue('hero_avatar', null, 'hero');

        return response()->json([
            'message' => 'Profile picture removed. Reverted to default placeholder.',
        ]);
    }

    /**
     * Upload / replace CV / Resume document.
     */
    public function uploadCv(Request $request): JsonResponse
    {
        $request->validate([
            'cv' => ['required', 'file', 'max:15360', 'mimes:pdf,doc,docx'],
        ]);

        $current = SiteSetting::getValue('cv_file_path');
        if ($current && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }

        $path = $request->file('cv')->store('documents', 'public');
        SiteSetting::setValue('cv_file_path', $path, 'site');

        return response()->json([
            'message' => 'CV document uploaded successfully.',
            'cv_file_path' => $path,
            'cv_file_url' => url(Storage::url($path)),
        ]);
    }

    /**
     * Remove custom CV.
     */
    public function removeCv(): JsonResponse
    {
        $current = SiteSetting::getValue('cv_file_path');
        if ($current && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }

        SiteSetting::setValue('cv_file_path', null, 'site');

        return response()->json([
            'message' => 'CV document removed.',
        ]);
    }
}
