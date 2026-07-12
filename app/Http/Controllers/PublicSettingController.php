<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class PublicSettingController extends Controller
{
    /**
     * Keys that are safe to expose on the public site. Payment gateway
     * credentials and internal-only settings are intentionally excluded.
     */
    private const PUBLIC_KEYS = [
        'site_name', 'site_email', 'site_phone', 'site_description',
        'nav_label_1', 'nav_label_2', 'nav_label_3', 'nav_label_4',
        'min_donation', 'max_donation', 'currency',
        'contact_address', 'contact_city', 'contact_state',
        'contact_postcode', 'contact_country', 'contact_hours',
        'social_facebook', 'social_twitter', 'social_instagram',
        'social_youtube', 'social_telegram',
        'home_about_text', 'about_mission_text',
    ];

    public function index(): JsonResponse
    {
        $settings = Setting::whereIn('key', self::PUBLIC_KEYS)->pluck('value', 'key');

        return response()->json($settings);
    }
}