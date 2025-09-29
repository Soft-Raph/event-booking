<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Services\PaymentService;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $req, $bookingId, PaymentService $svc)
    {
        $booking = Booking::with('ticket','user')->findOrFail($bookingId);
        if ($booking->user_id !== $req->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }

        $result = $svc->charge($booking);
        $payment = Payment::create([
            'booking_id'=>$booking->id,
            'amount'=>$result['amount'],
            'status'=>$result['status'],
        ]);

        if ($result['success']) {
            $booking->update(['status'=>'confirmed']);
            $booking->user->notify(new BookingConfirmedNotification($booking));
        }

        return $this->ok($payment, 'Payment processed');
    }

    public function show($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);
        return $this->ok($payment);
    }
}
