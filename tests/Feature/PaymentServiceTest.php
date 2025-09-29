<?php

namespace Tests\Feature;

use App\Models\{Booking, Ticket};
use App\Services\PaymentService;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    /** @test */
    public function it_calculates_amount_correctly()
    {
        $booking = Booking::factory()
            ->for(Ticket::factory(['price' => 100]))
            ->create(['quantity' => 2]);

        $svc = new PaymentService();
        $res = $svc->charge($booking);

        $this->assertArrayHasKey('success', $res);
        $this->assertArrayHasKey('amount', $res);
        $this->assertArrayHasKey('status', $res);

        $this->assertEquals(200.0, $res['amount']); // 100 * 2
    }
}
