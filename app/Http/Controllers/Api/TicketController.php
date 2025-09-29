<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(StoreTicketRequest $request, $event_id)
    {
        $event = Event::findOrFail($event_id);
        if ($request->user()->role === 'organizer' && $event->created_by !== $request->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        $data = $request->validated() + ['event_id' => $event->id];
        $ticket = Ticket::create($data);
        return $this->ok($ticket, 'Ticket created');
    }

    public function update(UpdateTicketRequest $request, $id)
    {
        $ticket = Ticket::with('event')->findOrFail($id);
        if ($request->user()->role === 'organizer' && $ticket->event->created_by !== $request->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        $ticket->update($request->validated());
        return $this->ok($ticket, 'Ticket updated');
    }

    public function destroy(Request $request, $id)
    {
        $ticket = Ticket::with('event')->findOrFail($id);
        if ($request->user()->role === 'organizer' && $ticket->event->created_by !== $request->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        $ticket->delete();
        return $this->ok(null, 'Ticket deleted');
    }
}
