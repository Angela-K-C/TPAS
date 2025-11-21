<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\TemporaryPass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemporaryPassApplicationLimitTest extends TestCase
{
    use RefreshDatabase;

    private function applicationPayload(): array
    {
        return [
            'visitor_name' => 'Visitor One',
            'national_id' => 'A1234567',
            'email' => 'visitor@example.com',
            'phone' => '0712345678',
            'host_name' => 'Host Example',
            'host_department' => 'Admissions',
            'purpose' => 'Campus tour',
            'visit_start' => now()->toDateString(),
            'visit_end' => now()->addDay()->toDateString(),
        ];
    }

    public function test_guest_cannot_submit_when_a_non_rejected_pass_exists(): void
    {
        $guest = Guest::factory()->create();

        TemporaryPass::factory()
            ->for($guest, 'passable')
            ->create([
                'status' => 'pending',
                'reason' => 'Strathmore Visit',
            ]);

        $response = $this->actingAs($guest, 'guest')
            ->post(route('guest.application.store'), $this->applicationPayload());

        $response->assertSessionHasErrors('application');

        $this->assertSame(1, TemporaryPass::where('passable_type', $guest->getMorphClass())
            ->where('passable_id', $guest->id)
            ->count());
    }

    public function test_guest_can_apply_again_after_rejection(): void
    {
        $guest = Guest::factory()->create();

        TemporaryPass::factory()
            ->for($guest, 'passable')
            ->create([
                'status' => 'rejected',
                'reason' => 'Strathmore Visit',
            ]);

        $response = $this->actingAs($guest, 'guest')
            ->post(route('guest.application.store'), $this->applicationPayload());

        $response->assertRedirect(route('guest.dashboard'))
            ->assertSessionHas('success');

        $this->assertSame(2, TemporaryPass::where('passable_type', $guest->getMorphClass())
            ->where('passable_id', $guest->id)
            ->count());

        $this->assertDatabaseHas('temporary_passes', [
            'passable_type' => $guest->getMorphClass(),
            'passable_id' => $guest->id,
            'status' => 'pending',
            'reason' => 'Strathmore Visit',
        ]);
    }
}
