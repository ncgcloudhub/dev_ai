<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
           // Fetch events for the authenticated user
        $events = Event::where('user_id', auth()->id())->get();
        Log::info('Fetched events:', ['events' => $events]);
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'category' => 'required|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'all_day' => 'required|boolean',
        ]);

        $data['user_id'] = auth()->id();

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'category' => 'required|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'all_day' => 'required|boolean',
        ]);

        $event->update($data);

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();

        return response()->json(null, 204);
    }
}
