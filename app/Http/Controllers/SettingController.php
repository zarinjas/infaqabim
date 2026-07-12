<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Whitelisted setting keys grouped by category. Only keys listed here can ever
     * be read or written via the settings API - this is the hard boundary that
     * prevents arbitrary key-value writes.
     */
    private const CATEGORIES = [
        'general' => [
            'site_name', 'site_email', 'site_phone', 'site_description', 'app_logo',
            'home_about_text', 'about_mission_text',
        ],
        'donation' => [
            'min_donation', 'max_donation', 'currency',
        ],
        'contact' => [
            'contact_address', 'contact_city', 'contact_state',
            'contact_postcode', 'contact_country', 'contact_hours',
        ],
        'social' => [
            'social_facebook', 'social_twitter', 'social_instagram',
            'social_youtube', 'social_telegram',
        ],
        'navbar' => [
            'nav_label_1', 'nav_label_2', 'nav_label_3', 'nav_label_4',
        ],
        'payment' => [
            'payment_method', // 'senangpay' | 'manual' | 'both'
            'senangpay_merchant_id',
            'senangpay_secret_key', // encrypted at rest, never returned in plaintext
            'senangpay_sandbox_mode',
        ],
    ];

    private function allowedKeys(): array
    {
        return array_merge(...array_values(self::CATEGORIES));
    }

    public function index(): JsonResponse
    {
        $rows = Setting::whereIn('key', $this->allowedKeys())->pluck('value', 'key');

        $settings = [];
        foreach ($this->allowedKeys() as $key) {
            $raw = $rows[$key] ?? null;

            if (Setting::isEncrypted($key)) {
                // Never expose the secret itself - only whether it's configured, plus a masked preview.
                $decrypted = Setting::decryptValue($raw);
                $settings[$key.'_configured'] = $decrypted !== null;
                $settings[$key.'_masked'] = Setting::mask($decrypted);
                continue;
            }

            $settings[$key] = $raw;
        }

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
            'nav_label_1' => 'nullable|string|max:50',
            'nav_label_2' => 'nullable|string|max:50',
            'nav_label_3' => 'nullable|string|max:50',
            'nav_label_4' => 'nullable|string|max:50',
            'home_about_text' => 'nullable|string',
            'about_mission_text' => 'nullable|string',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'payment_method' => 'nullable|in:senangpay,manual,both',
            'senangpay_merchant_id' => 'nullable|string|max:255',
            'senangpay_secret_key' => 'nullable|string|max:255',
            'senangpay_sandbox_mode' => 'nullable|boolean',
        ]);

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            if ($key === 'app_logo') {
                if ($request->hasFile('app_logo')) {
                    $path = $request->file('app_logo')->store('settings', 'public');
                    $value = $path;
                } else {
                    continue; // Skip if it's not a file upload
                }
            }

            if (Setting::isEncrypted($key)) {
                // Skip re-saving if the frontend sends back the masked placeholder untouched.
                if (str_contains($value, '•')) {
                    continue;
                }
                $value = Setting::encryptValue($value);
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return $this->index();
    }
}