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
        // Get the start and end date strings from the request
        $startDateString = $request->input('start');
        $endDateString = $request->input('end');
    
        // Clean the date strings by removing timezone information
        $cleanedStartDateString = preg_replace('/ GMT.*$/', '', $startDateString);
        
        // Convert the cleaned start date string to DateTime object
        $startDate = \DateTime::createFromFormat('D M d Y H:i:s', $cleanedStartDateString);
    
        // Check if parsing was successful for the start date
        if (!$startDate) {
            Log::error('Failed to parse start date:', ['start' => $startDateString]);
            return response()->json(['error' => 'Invalid start date format'], 422);
        }
    
        // Handle cases where the end date might be null
        if ($endDateString) {
            $cleanedEndDateString = preg_replace('/ GMT.*$/', '', $endDateString);
            $endDate = \DateTime::createFromFormat('D M d Y H:i:s', $cleanedEndDateString);
    
            // If end date parsing fails, log the error and return a response
            if (!$endDate) {
                Log::error('Failed to parse end date:', ['end' => $endDateString]);
                return response()->json(['error' => 'Invalid end date format'], 422);
            }
    
            // Format the end date for storage
            $formattedEndDate = $endDate->format('Y-m-d H:i:s');
        } else {
            // Set end date to null if not provided
            $formattedEndDate = null;
        }
    
        // Merge the cleaned-up dates into the request
        $request->merge([
            'start' => $startDate->format('Y-m-d H:i:s'), // Format the start date for storage
            'end' => $formattedEndDate, // Use formatted end date or null
            'all_day' => filter_var($request->input('all_day'), FILTER_VALIDATE_BOOLEAN)
        ]);
    
        // Validate the request
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start', // Ensure end date is after or equal to the start date
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

    Log::error('Request: sucess controller');
    // Get the start and end date strings from the request
    $startDateString = $request->input('start');
    $endDateString = $request->input('end');

    // Clean the date strings by removing timezone information
    $cleanedStartDateString = preg_replace('/ GMT.*$/', '', $startDateString);

    // Convert the cleaned start date string to DateTime object
    $startDate = \DateTime::createFromFormat('D M d Y H:i:s', $cleanedStartDateString);

    // Check if parsing was successful for the start date
    if (!$startDate) {
        Log::error('Failed to parse start date:', ['start' => $startDateString]);
        return response()->json(['error' => 'Invalid start date format'], 422);
    }

    // Handle cases where the end date might be null
    if ($endDateString) {
        $cleanedEndDateString = preg_replace('/ GMT.*$/', '', $endDateString);
        $endDate = \DateTime::createFromFormat('D M d Y H:i:s', $cleanedEndDateString);

        // If end date parsing fails, log the error and return a response
        if (!$endDate) {
            Log::error('Failed to parse end date:', ['end' => $endDateString]);
            return response()->json(['error' => 'Invalid end date format'], 422);
        }

        // Format the end date for storage
        $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    } else {
        // Set end date to null if not provided
        $formattedEndDate = null;
    }

    // Merge the cleaned-up dates into the request
    $request->merge([
        'start' => $startDate->format('Y-m-d H:i:s'), // Format the start date for storage
        'end' => $formattedEndDate, // Use formatted end date or null
        'all_day' => filter_var($request->input('all_day'), FILTER_VALIDATE_BOOLEAN)
    ]);

    // Validate the request
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'start' => 'required|date',
        'end' => 'nullable|date|after_or_equal:start', // Ensure end date is after or equal to start date
        'category' => 'required|string',
        'location' => 'nullable|string',
        'description' => 'nullable|string',
        'all_day' => 'required|boolean',
    ]);

    // Update the event
    $event->update($data);

    return response()->json($event);
}


public function updateDrag(Request $request, Event $event)
{
    Log::info('Request received for updating event:', $request->all());

    // Get the start and end date strings from the request
    $startDateString = $request->input('start');
    $endDateString = $request->input('end');

    // Convert the start date string to a DateTime object
    try {
        $startDate = new \DateTime($startDateString);
    } catch (\Exception $e) {
        Log::error('Failed to parse start date:', ['start' => $startDateString, 'error' => $e->getMessage()]);
        return response()->json(['error' => 'Invalid start date format'], 422);
    }

    // Handle cases where the end date might be null
    if ($endDateString) {
        try {
            $endDate = new \DateTime($endDateString);
            $formattedEndDate = $endDate->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::error('Failed to parse end date:', ['end' => $endDateString, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid end date format'], 422);
        }
    } else {
        // Set end date to null if not provided
        $formattedEndDate = null;
    }

    // Merge the cleaned-up dates into the request
    $request->merge([
        'start' => $startDate->format('Y-m-d H:i:s'), // Format the start date for storage
        'end' => $formattedEndDate, // Use formatted end date or null
        'all_day' => filter_var($request->input('all_day'), FILTER_VALIDATE_BOOLEAN)
    ]);

    // Validate the request
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'start' => 'required|date',
        'end' => 'nullable|date|after_or_equal:start', // Ensure end date is after or equal to start date
        'category' => 'required|string',
        'location' => 'nullable|string',
        'description' => 'nullable|string',
        'all_day' => 'required|boolean',
    ]);

    // Update the event
    $event->update($data);

    return response()->json($event);
}




    public function destroy(Event $event)
    {

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully.'], 200);
    }


}
