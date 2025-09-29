<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $key = 'events:'.md5($request->fullUrl());
        $events = Cache::remember($key, now()->addMinutes(5), function() use ($request){
            return Event::query()
                ->searchByTitle($request->query('q'))
                ->when($request->date_from || $request->date_to,
                    fn($q)=>$q->filterByDate($request->date_from, $request->date_to))
                ->when($request->location, fn($q, $loc)=>$q->where('location','LIKE', "%$loc%"))
                ->orderBy('date','asc')
                ->paginate(10);
        });
        return $this->ok($events);
    }

    public function show($id)
    {
        $event = Event::with('creator')->findOrFail($id);
        return $this->ok($event);
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $event = Event::create($data);
        return $this->ok($event, 'Event created');
    }

    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        if ($request->user()->role === 'organizer' && $event->created_by !== $request->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        $event->update($request->validated());
        return $this->ok($event, 'Event updated');
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if ($request->user()->role === 'organizer' && $event->created_by !== $request->user()->id) {
            return response()->json(['success'=>false,'message'=>'Forbidden'], 403);
        }
        $event->delete();
        return $this->ok(null, 'Event deleted');
    }
}
