<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

/**
 * SenangPay payment gateway integration.
 *
 * IMPORTANT: This implementation follows SenangPay's long-standing publicly
 * documented "Payment Form" integration (the same hash scheme used by their
 * official WooCommerce/WHMCS plugins): HMAC-SHA256 over a concatenated string,
 * keyed by the merchant secret key.
 *
 * SenangPay rebranded to "senangPay-DOKU" and gated their live API reference
 * behind a merchant login, so this could not be verified against the current
 * dashboard docs at implementation time. Before going live:
 *   1. Log in to the merchant dashboard and confirm the exact payment URL,
 *      hash concatenation order, and callback parameter names.
 *   2. Test end-to-end in sandbox mode with real sandbox credentials.
 *   3. Update PAYMENT_URL / CALLBACK hash logic below if they differ.
 */
class SenangPayService
{
    private const PRODUCTION_URL = 'https://app.senangpay.my/payment/';
    private const SANDBOX_URL = 'https://sandbox.senangpay.my/payment/';

    public function isConfigured(): bool
    {
        return (bool) $this->merchantId() && $this->secretKey() !== null;
    }

    public function merchantId(): ?string
    {
        return Setting::where('key', 'senangpay_merchant_id')->value('value');
    }

    public function secretKey(): ?string
    {
        $raw = Setting::where('key', 'senangpay_secret_key')->value('value');

        return Setting::decryptValue($raw);
    }

    public function isSandbox(): bool
    {
        $value = Setting::where('key', 'senangpay_sandbox_mode')->value('value');

        return $value === null || $value === '1' || $value === '1.0' || $value === 'true';
    }

    /**
     * Build the redirect URL that sends the donor to SenangPay's hosted payment page.
     */
    public function buildPaymentUrl(string $orderId, float $amount, string $detail, string $name, string $email, string $phone): string
    {
        $merchantId = $this->merchantId();
        $secretKey = $this->secretKey();

        if (! $merchantId || ! $secretKey) {
            throw new \RuntimeException('SenangPay is not configured.');
        }

        $amountFormatted = number_format($amount, 2, '.', '');
        $hash = $this->generatePaymentHash($secretKey, $orderId, $detail, $amountFormatted);

        $baseUrl = $this->isSandbox() ? self::SANDBOX_URL : self::PRODUCTION_URL;

        $query = http_build_query([
            'detail' => $detail,
            'amount' => $amountFormatted,
            'order_id' => $orderId,
            'hash' => $hash,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ]);

        return $baseUrl.$merchantId.'?'.$query;
    }

    private function generatePaymentHash(string $secretKey, string $orderId, string $detail, string $amount): string
    {
        return hash_hmac('sha256', $secretKey.$orderId.$detail.$amount, $secretKey);
    }

    /**
     * Verify a callback/return hash sent by SenangPay. Uses hash_equals for
     * timing-safe comparison - never compare secrets/hashes with `===`.
     */
    public function verifyCallbackHash(string $statusId, string $orderId, string $transactionId, string $msg, string $receivedHash): bool
    {
        $secretKey = $this->secretKey();

        if (! $secretKey) {
            Log::warning('SenangPay callback received but no secret key configured.');

            return false;
        }

        $expected = hash_hmac('sha256', $secretKey.$statusId.$orderId.$transactionId.$msg, $secretKey);

        return hash_equals($expected, $receivedHash);
    }
}
