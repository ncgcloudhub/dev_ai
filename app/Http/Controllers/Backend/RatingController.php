<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RatingTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    
        $rating = RatingTemplate::updateOrCreate(
            ['user_id' => auth()->id(), 'template_id' => $request->template_id],
            ['rating' => $request->rating]
        );
    
        return response()->json(['message' => 'Rating submitted successfully']);
    }
}
