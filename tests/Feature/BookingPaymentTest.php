<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /** @test */
    public function it_books_a_ticket_and_pays()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        Sanctum::actingAs($customer);

        $event = Event::factory()->create([
            'created_by' => User::factory()->create(['role' => 'organizer']),
        ]);

        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price'    => 50,
            'quantity' => 10,
        ]);

        $res = $this->postJson("/api/tickets/{$ticket->id}/bookings", ['quantity' => 2])
            ->assertOk();

        $bookingId = $res->json('data.id');

        $this->postJson("/api/bookings/{$bookingId}/payment")
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
