<?php

namespace Tests\Feature;

use App\Models\{Booking, Ticket};
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_amount_correctly()
    {
        $booking = \App\Models\Booking::factory()
            ->for(\App\Models\Ticket::factory(['price' => 100]))
            ->create(['quantity' => 2]);

        $res = (new \App\Services\PaymentService())->charge($booking);

        $this->assertEquals(200.0, $res['amount']);
    }
}
