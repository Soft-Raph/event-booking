<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('ticket.event')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate(10);
        return $this->ok($bookings);
    }

    public function store(StoreBookingRequest $req, $ticketId)
    {
        return DB::transaction(function() use ($req, $ticketId){
            $ticket = Ticket::lockForUpdate()->findOrFail($ticketId);
            if($ticket->quantity < $req->quantity){
                return response()->json(['success'=>false,'message'=>'Not enough tickets'], 422);
            }
            $booking = Booking::create([
                'user_id'=>$req->user()->id,
                'ticket_id'=>$ticket->id,
                'quantity'=>$req->quantity,
                'status'=>'pending',
            ]);
            $ticket->decrement('quantity', $req->quantity);
            return $this->ok($booking, 'Booking created');
        });
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        if ($booking->status === 'confirmed') {
            return response()->json(['success'=>false,'message'=>'Cannot cancel a confirmed booking'], 422);
        }
        $booking->status = 'cancelled';
        $booking->save();
        return $this->ok($booking, 'Booking cancelled');
    }
}
