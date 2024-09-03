<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestModuleFeedbackController extends Controller
{
    public function templateFeedback(Request $request)
    {
        $module = "Template";
        $activity = "Template Requested";
        $text = $request->input('text');

        // Use the helper function to save feedback
        $result = saveModuleFeedback($module, $text);
        log_activity($activity);
        // Redirect back with a message
        if ($result === "feedback-saved-successfully") {
            return redirect()->back()->with('success', 'Your feedback has been submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'There was an issue submitting your feedback.');
        }
    }
}
