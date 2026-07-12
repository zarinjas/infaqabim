<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $admin = Admin::factory()->create(['password' => 'password123']);

        $response = $this->postJson('/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonPath('admin.email', $admin->email);
        $this->assertAuthenticated('admins');
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        $admin = Admin::factory()->create(['password' => 'password123']);

        $response = $this->postJson('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $this->assertGuest('admins');
    }

    public function test_login_response_does_not_leak_whether_email_or_password_was_wrong(): void
    {
        $admin = Admin::factory()->create(['password' => 'password123']);

        $wrongPassword = $this->postJson('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $wrongEmail = $this->postJson('/admin/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertSame($wrongPassword->json('message'), $wrongEmail->json('message'));
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        $admin = Admin::factory()->create(['password' => 'password123']);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/admin/login', [
                'email' => $admin->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->postJson('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }

    public function test_password_must_be_at_least_eight_characters(): void
    {
        $response = $this->postJson('/admin/login', [
            'email' => 'someone@example.com',
            'password' => 'short',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_guest_cannot_access_protected_admin_routes(): void
    {
        $response = $this->getJson('/admin/dashboard');

        $response->assertStatus(401);
    }

    public function test_authenticated_admin_can_access_protected_routes(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admins')->getJson('/admin/dashboard');

        $response->assertStatus(200);
    }
}
