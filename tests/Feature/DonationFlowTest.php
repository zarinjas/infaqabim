<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DonationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_donation_submission_forces_pending_status_regardless_of_input(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->postJson('/api/donations', [
            'campaign_id' => $campaign->id,
            'donor_name' => 'Ali bin Abu',
            'donor_email' => 'ali@example.com',
            'donor_phone' => '0123456789',
            'amount' => 50,
            'payment_method' => 'manual',
            'status' => 'completed', // attempted mass-assignment of status - must be ignored
            'receipt_image' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('donations', [
            'donor_email' => 'ali@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_manual_donation_requires_receipt_image(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->postJson('/api/donations', [
            'campaign_id' => $campaign->id,
            'donor_name' => 'Ali bin Abu',
            'donor_email' => 'ali@example.com',
            'donor_phone' => '0123456789',
            'amount' => 50,
            'payment_method' => 'manual',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['receipt_image']);
    }

    public function test_senangpay_donation_fails_gracefully_when_not_configured(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->postJson('/api/donations', [
            'campaign_id' => $campaign->id,
            'donor_name' => 'Ali bin Abu',
            'donor_email' => 'ali@example.com',
            'donor_phone' => '0123456789',
            'amount' => 50,
            'payment_method' => 'senangpay',
        ]);

        $response->assertStatus(422);
    }

    public function test_approving_donation_increments_campaign_collected_amount(): void
    {
        $admin = Admin::factory()->create();
        $campaign = Campaign::factory()->create(['collected_amount' => 0]);
        $donation = Donation::factory()->create([
            'campaign_id' => $campaign->id,
            'amount' => 100,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admins')
            ->patchJson("/admin/donations/{$donation->id}/approve");

        $response->assertStatus(200);
        $this->assertSame('completed', $donation->refresh()->status);
        $this->assertEquals(100, $campaign->refresh()->collected_amount);
    }

    public function test_rejecting_donation_does_not_change_campaign_amount(): void
    {
        $admin = Admin::factory()->create();
        $campaign = Campaign::factory()->create(['collected_amount' => 0]);
        $donation = Donation::factory()->create([
            'campaign_id' => $campaign->id,
            'amount' => 100,
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'admins')
            ->patchJson("/admin/donations/{$donation->id}/reject")
            ->assertStatus(200);

        $this->assertSame('failed', $donation->refresh()->status);
        $this->assertEquals(0, $campaign->refresh()->collected_amount);
    }

    public function test_guest_cannot_approve_donations(): void
    {
        $donation = Donation::factory()->create(['status' => 'pending']);

        $response = $this->patchJson("/admin/donations/{$donation->id}/approve");

        $response->assertStatus(401);
        $this->assertSame('pending', $donation->refresh()->status);
    }
}
