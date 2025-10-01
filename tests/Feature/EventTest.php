<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function it_lists_events_with_pagination()
    {
        $user = User::factory()->create(['role' => 'customer']);
        Sanctum::actingAs($user);

        $this->getJson('/api/events')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
