<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_read_admin_settings(): void
    {
        $this->getJson('/admin/settings')->assertStatus(401);
    }

    public function test_admin_can_update_general_settings(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admins')->postJson('/admin/settings', [
            'site_name' => 'INFAQABIM Updated',
            'nav_label_1' => 'Utama',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('settings', ['key' => 'site_name', 'value' => 'INFAQABIM Updated']);
        $this->assertDatabaseHas('settings', ['key' => 'nav_label_1', 'value' => 'Utama']);
    }

    public function test_senangpay_secret_key_is_encrypted_at_rest(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admins')->postJson('/admin/settings', [
            'senangpay_secret_key' => 'super-secret-value',
        ])->assertStatus(200);

        $raw = Setting::where('key', 'senangpay_secret_key')->value('value');

        $this->assertNotSame('super-secret-value', $raw);
        $this->assertSame('super-secret-value', Setting::decryptValue($raw));
    }

    public function test_settings_index_never_exposes_plaintext_secret(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admins')->postJson('/admin/settings', [
            'senangpay_secret_key' => 'super-secret-value',
        ]);

        $response = $this->actingAs($admin, 'admins')->getJson('/admin/settings');

        $response->assertStatus(200);
        $response->assertJsonMissing(['senangpay_secret_key' => 'super-secret-value']);
        $this->assertTrue($response->json('senangpay_secret_key_configured'));
        $this->assertStringNotContainsString('super-secret-value', $response->getContent());
    }

    public function test_public_settings_endpoint_excludes_payment_credentials(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'INFAQABIM']);
        Setting::create(['key' => 'senangpay_secret_key', 'value' => Setting::encryptValue('super-secret-value')]);
        Setting::create(['key' => 'senangpay_merchant_id', 'value' => '190100000000']);

        $response = $this->getJson('/api/settings');

        $response->assertStatus(200);
        $response->assertJsonPath('site_name', 'INFAQABIM');
        $this->assertArrayNotHasKey('senangpay_secret_key', $response->json());
        $this->assertArrayNotHasKey('senangpay_merchant_id', $response->json());
    }

    public function test_arbitrary_unwhitelisted_key_cannot_be_written(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admins')->postJson('/admin/settings', [
            'site_name' => 'INFAQABIM',
            'some_malicious_key' => 'hacked',
        ])->assertStatus(200);

        $this->assertDatabaseMissing('settings', ['key' => 'some_malicious_key']);
    }
}
