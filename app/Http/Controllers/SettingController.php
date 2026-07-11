<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::pluck('value', 'key');
        return response()->json($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:50',
            'site_description' => 'nullable|string',
            'min_donation' => 'nullable|numeric|min:0',
            'max_donation' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'contact_address' => 'nullable|string|max:255',
            'contact_city' => 'nullable|string|max:255',
            'contact_state' => 'nullable|string|max:255',
            'contact_postcode' => 'nullable|string|max:20',
            'contact_country' => 'nullable|string|max:255',
            'contact_hours' => 'nullable|string|max:255',
            'social_facebook' => 'nullable|string|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'social_telegram' => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        $settings = Setting::pluck('value', 'key');
        return response()->json($settings);
    }
}
