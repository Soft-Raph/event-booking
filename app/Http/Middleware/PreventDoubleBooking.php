<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;

class PreventDoubleBooking
{
    public function handle(Request $request, Closure $next)
    {
        $ticketId = (int) ($request->route('id') ?? $request->ticket_id);
        if ($request->user()) {
            $exists = Booking::where('user_id', $request->user()->id)
                ->where('ticket_id', $ticketId)
                ->whereIn('status', ['pending','confirmed'])
                ->exists();
            if ($exists) {
                return response()->json(['success'=>false,'message'=>'Already booked this ticket'], 422);
            }
        }
        return $next($request);
    }
}
