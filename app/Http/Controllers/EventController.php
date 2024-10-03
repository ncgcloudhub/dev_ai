<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DateTime; 

class EventController extends Controller
{
    public function index()
    {
        // Fetch events for the authenticated user
        $events = Event::where('user_id', auth()->id())->get();
        // Log::info('Fetched events:', ['events' => $events]);
        return response()->json($events);
    }

    public function store(Request $request)
    {
        Log::info('Request Data:', $request->all());
    
        // Get the start date string from the request
        $startDateString = $request->input('start');
    
        // Clean the date string by removing timezone information
        // This handles different possible formats
        $cleanedStartDateString = preg_replace('/ GMT.*$/', '', $startDateString);
    
        // Convert the cleaned start date string to a DateTime object
        $startDate = \DateTime::createFromFormat('D M d Y H:i:s', $cleanedStartDateString);
    
        // Check if parsing was successful
        if (!$startDate) {
            Log::error('Failed to parse start date:', ['start' => $startDateString]);
            return response()->json(['error' => 'Invalid start date format'], 422);
        }
    
        // Merge the cleaned-up date into the request
        $request->merge([
            'start' => $startDate->format('Y-m-d H:i:s'), // Format the date for storage
            'all_day' => filter_var($request->input('all_day'), FILTER_VALIDATE_BOOLEAN)
        ]);
    
        // Validate the request
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'category' => 'required|string',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'all_day' => 'required|boolean',
        ]);
    
        // Create the event
        $event = Event::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'start' => $data['start'],
            'end' => $data['end'],
            'category' => $data['category'],
            'location' => $data['location'],
            'description' => $data['description'],
            'all_day' => $data['all_day'],
        ]);
    
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
