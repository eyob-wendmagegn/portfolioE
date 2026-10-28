<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            // Auto-normalize URLs if the user omitted http:// or https://
            if (is_string($value) && trim($value) !== '') {
                $trimmed = trim($value);
                if (in_array($key, ['contact_linkedin', 'contact_github', 'social_twitter', 'cv_file_url']) || str_ends_with($key, '_url')) {
                    if (!preg_match('~^https?://~i', $trimmed) && !str_starts_with($trimmed, '/')) {
                        $value = 'https://' . $trimmed;
                    }
                }
            }

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

    /**
     * Send a test notification email to phone.
     */
    public function sendTestEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $targetEmail = $request->input('email');

        try {
            Mail::raw("Hi Eyob! This is a real-time test notification from your Eyob.dev portfolio system.\n\nTime sent: " . now()->toDayDateTimeString() . "\n\nIf you see this on your phone, real-time push notifications are working perfectly!", function ($message) use ($targetEmail) {
                $message->to($targetEmail)
                    ->subject("🔔 Real-Time Notification Test — Eyob.dev Portfolio");
            });

            return response()->json([
                'message' => "Test notification successfully sent to {$targetEmail}! Check your phone's inbox and notifications.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed sending test email: ' . $e->getMessage() . '. Please verify your SMTP settings in .env.',
            ], 500);
        }
    }
}
