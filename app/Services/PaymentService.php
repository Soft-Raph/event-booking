<?php

namespace App\Services;

use App\Models\Booking;

class PaymentService
{
    public function charge(Booking $booking): array
    {
        $success = random_int(1, 10) <= 8; // ~80% success (mock gateway)
        $amount = $booking->quantity * $booking->ticket->price;

        return [
            'success' => $success,
            'amount'  => $amount,
            'status'  => $success ? 'success' : 'failed',
        ];
    }
}
