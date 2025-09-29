<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventTest extends TestCase
{
    /** @test */
    public function it_lists_events_with_pagination()
    {
        $user = User::factory()->create(['role' => 'customer']);

        Sanctum::actingAs($user);

        $this->getJson('/api/events')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
