<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SenangPayWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function configureSenangPay(string $secret = 'test-secret-key'): void
    {
        Setting::create(['key' => 'senangpay_merchant_id', 'value' => '190100000000']);
        Setting::create(['key' => 'senangpay_secret_key', 'value' => Setting::encryptValue($secret)]);
        Setting::create(['key' => 'senangpay_sandbox_mode', 'value' => '1']);
    }

    private function hash(string $secret, string $statusId, string $orderId, string $transactionId, string $msg): string
    {
        return hash_hmac('sha256', $secret.$statusId.$orderId.$transactionId.$msg, $secret);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $this->configureSenangPay();
        $campaign = Campaign::factory()->create();
        $donation = Donation::factory()->create([
            'campaign_id' => $campaign->id,
            'reference_number' => 'DON-1-123',
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/senangpay/callback', [
            'status_id' => '1',
            'order_id' => 'DON-1-123',
            'transaction_id' => 'TXN123',
            'msg' => 'ok',
            'hash' => 'totally-invalid-hash',
        ]);

        $response->assertStatus(403);
        $this->assertSame('pending', $donation->refresh()->status);
    }

    public function test_webhook_accepts_valid_signature_and_completes_donation(): void
    {
        $secret = 'test-secret-key';
        $this->configureSenangPay($secret);
        $campaign = Campaign::factory()->create(['collected_amount' => 0]);
        $donation = Donation::factory()->create([
            'campaign_id' => $campaign->id,
            'amount' => 75,
            'reference_number' => 'DON-1-123',
            'status' => 'pending',
        ]);

        $hash = $this->hash($secret, '1', 'DON-1-123', 'TXN123', 'ok');

        $response = $this->postJson('/api/senangpay/callback', [
            'status_id' => '1',
            'order_id' => 'DON-1-123',
            'transaction_id' => 'TXN123',
            'msg' => 'ok',
            'hash' => $hash,
        ]);

        $response->assertStatus(200);
        $this->assertSame('completed', $donation->refresh()->status);
        $this->assertEquals(75, $campaign->refresh()->collected_amount);
    }

    public function test_webhook_is_idempotent_and_does_not_double_increment(): void
    {
        $secret = 'test-secret-key';
        $this->configureSenangPay($secret);
        $campaign = Campaign::factory()->create(['collected_amount' => 0]);
        $donation = Donation::factory()->create([
            'campaign_id' => $campaign->id,
            'amount' => 75,
            'reference_number' => 'DON-1-123',
            'status' => 'pending',
        ]);

        $hash = $this->hash($secret, '1', 'DON-1-123', 'TXN123', 'ok');
        $payload = [
            'status_id' => '1',
            'order_id' => 'DON-1-123',
            'transaction_id' => 'TXN123',
            'msg' => 'ok',
            'hash' => $hash,
        ];

        $this->postJson('/api/senangpay/callback', $payload)->assertStatus(200);
        $this->postJson('/api/senangpay/callback', $payload)->assertStatus(200);

        $this->assertEquals(75, $campaign->refresh()->collected_amount);
    }

    public function test_webhook_marks_donation_failed_on_non_success_status(): void
    {
        $secret = 'test-secret-key';
        $this->configureSenangPay($secret);
        $campaign = Campaign::factory()->create(['collected_amount' => 0]);
        $donation = Donation::factory()->create([
            'campaign_id' => $campaign->id,
            'amount' => 75,
            'reference_number' => 'DON-1-123',
            'status' => 'pending',
        ]);

        $hash = $this->hash($secret, '0', 'DON-1-123', 'TXN123', 'failed');

        $this->postJson('/api/senangpay/callback', [
            'status_id' => '0',
            'order_id' => 'DON-1-123',
            'transaction_id' => 'TXN123',
            'msg' => 'failed',
            'hash' => $hash,
        ])->assertStatus(200);

        $this->assertSame('failed', $donation->refresh()->status);
        $this->assertEquals(0, $campaign->refresh()->collected_amount);
    }

    public function test_webhook_returns_404_for_unknown_order_id(): void
    {
        $secret = 'test-secret-key';
        $this->configureSenangPay($secret);

        $hash = $this->hash($secret, '1', 'DON-999-999', 'TXN123', 'ok');

        $this->postJson('/api/senangpay/callback', [
            'status_id' => '1',
            'order_id' => 'DON-999-999',
            'transaction_id' => 'TXN123',
            'msg' => 'ok',
            'hash' => $hash,
        ])->assertStatus(404);
    }
}
