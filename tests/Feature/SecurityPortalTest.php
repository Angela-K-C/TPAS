<?php

/**
 * Security bundle copy:
 * Feature smoke tests for the guard login + lookup workflow.
 */

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\SecurityStaff;
use App\Models\TemporaryPass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guard_can_login_and_access_portal(): void
    {
        $staff = SecurityStaff::factory()->create([
            'email' => 'guard@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('security.login.submit'), [
            'email' => 'guard@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('security.verify'));
        $this->assertAuthenticated('security');

        $portal = $this->actingAs($staff, 'security')->get(route('security.verify'));
        $portal->assertOk()->assertSee('Scan or enter a QR token');
    }

    public function test_guard_lookup_returns_pass_metadata(): void
    {
        $staff = SecurityStaff::factory()->create();

        $guest = Guest::create([
            'name' => 'Visitor Example',
            'email' => 'visitor@example.com',
        ]);

        $token = (string) Str::uuid();
        TemporaryPass::create([
            'passable_type' => Guest::class,
            'passable_id' => $guest->id,
            'status' => 'approved',
            'reason' => 'campus_event',
            'qr_code_token' => $token,
            'valid_from' => now(),
            'valid_until' => now()->addHours(4),
        ]);

        $response = $this->actingAs($staff, 'security')
            ->postJson(route('security.lookup'), ['token' => $token]);

        $response->assertOk()
            ->assertJson([
                'found' => true,
                'status' => 'approved',
                'holder_email' => 'visitor@example.com',
                'reason' => 'Attending Campus Event',
            ]);
    }
}
