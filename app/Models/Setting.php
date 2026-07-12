<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Setting keys whose value must be encrypted at rest (e.g. payment gateway secrets).
     * These are never returned in plaintext via the API - only a "configured" flag is exposed.
     */
    public const ENCRYPTED_KEYS = [
        'senangpay_secret_key',
        'billplz_secret_key',
        'bayarcash_secret_key',
    ];

    public static function isEncrypted(string $key): bool
    {
        return in_array($key, self::ENCRYPTED_KEYS, true);
    }

    public static function encryptValue(string $value): string
    {
        return Crypt::encryptString($value);
    }

    public static function decryptValue(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Mask a secret for display purposes, e.g. "sk_live_xxx1234" -> "••••••••1234".
     */
    public static function mask(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $length = strlen($value);
        if ($length <= 4) {
            return str_repeat('•', $length);
        }

        return str_repeat('•', $length - 4).substr($value, -4);
    }
}
