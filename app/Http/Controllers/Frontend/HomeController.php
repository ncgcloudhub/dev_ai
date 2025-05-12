<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AISettings;
use Illuminate\Http\Request;
use App\Models\DalleImageGenerate;
use App\Models\DynamicPage;
use App\Models\EducationTools;
use App\Models\EducationToolsCategory;
use App\Models\FavoriteImageDalle;
use App\Models\GradeClass;
use App\Models\Job;
use App\Models\Jokes;
use App\Models\LikedImagesDalle;
use App\Models\NewsLetter;
use Illuminate\Support\Carbon;
use App\Models\PrivacyPolicy;
use App\Models\PromptLibrary;
use App\Models\PromptLibraryCategory;
use App\Models\StableDiffusionGeneratedImage;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\TermsConditions;
use App\Models\ToolGeneratedContent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use OpenAI;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Parsedown;


class HomeController extends Controller
{
    // Image Gallery Front End Page
    public function AIImageGallery(Request $request)
    {
        $query = DalleImageGenerate::withCount(['likes', 'favorites']);
        $stableImagesQuery = StableDiffusionGeneratedImage::withCount('stableDiffusionLike');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereRaw('LOWER(prompt) LIKE ?', ['%' . strtolower($search) . '%']);
            $stableImagesQuery->whereRaw('LOWER(prompt) LIKE ?', ['%' . strtolower($search) . '%']);
        }
    
        if ($request->has('resolution') && !empty($request->input('resolution'))) {
            $resolution = $request->input('resolution');
            $query->where('resolution', $resolution);
        }
    
        if ($request->has('style') && !empty($request->input('style'))) {
            $style = $request->input('style');
            $query->where('style', 'LIKE', '%' . $style . '%');
        }
    
    
        $images = $query->latest()->paginate(20);
        $stableImages = $stableImagesQuery->latest()->paginate(20);
    
        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
            $image->liked_by_user = LikedImagesDalle::where('user_id', Auth::id())->where('image_id', $image->id)->exists();
            $image->favorited_by_user = FavoriteImageDalle::where('user_id', Auth::id())->where('image_id', $image->id)->exists();
        }
    
        if ($request->ajax()) {
            $imageGalleryPartial = view('frontend.image_gallery_partial', compact('images'))->render();
            $stableImagesPartial = view('frontend.stable_images_partial_frontend', compact('stableImages'))->render();
            return response()->json([
                'imagesPartial' => $imageGalleryPartial,
                'stableImagesPartial' => $stableImagesPartial,
            ]);
        }
    
        return view('frontend.ai_image_gallery', compact('images','stableImages'));
    }
    
    //Jokes (Magic Ball)
    public function MagicBallJokes()
    {
        $jokes = Jokes::latest()->get();
        return view('backend.magic_ball_jokes.manage_jokes', compact('jokes'));
    }

    public function MagicBallJokeStore(Request $request)
    {   

    // Log received joke content for debugging
    Log::info('Received Joke Content:', ['content' => $request->joke_content]);

    // Split the joke content by new lines
    $points = preg_split('/\r\n|\r|\n/', $request->joke_content);

     // Log the split points for debugging
     Log::info('Split Points:', ['points' => $points]);
     $category = $request->category ?? 'General';
     $type = $request->type;

    // Loop through the points array and store each point as a separate entry in the database
    foreach ($points as $point) {
        // Trim whitespace and avoid storing empty points
        Log::info('Storing Point:', ['point' => $point]);

        $point = trim($point);
        if ($point) {
            Jokes::create([
                'type' => $type, 
                'category' => $category, 
                'content' => $point,  
            ]);
        }
    }

    // Return a success response
    return response()->json(['message' => 'Jokes added successfully!']);
}


    public function MagicBallJokeEdit($id)
    {
        $countries = Jokes::latest()->get();
        $country = Jokes::findOrFail($id);
        return view('backend.user.block_edit_admin', compact('countries', 'country'));
    }


    public function MagicBallJokeUpdate(Request $request)
    {

        $id = $request->id;

        Jokes::findOrFail($id)->update([
            'category' => $request->category,
            'content' => $request->content,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Joke Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 
    


    public function MagicBallJokeDelete($id)
    {
        $joke = Jokes::findOrFail($id);
        $joke->delete();

        return redirect()->route('magic.ball.jokes')->with('success', 'Joke deleted successfully');
    }
    
    public function generateAiJoke(Request $request)
    {   
        $validated = $request->validate([
            'type' => 'required|string',
            'category' => 'required|string',
            'points' => 'required|integer',
        ]);

        $user = auth()->user();
        $openaiModel = $user->selected_model;

        // Get the category and points from the request
        $type = $validated['type'];
        $category = $validated['category'];
        $points = $validated['points'];

        // Get the user-provided content, if available (empty string if not provided)
        $userContent = $request->input('content', '');

        // Start building the AI message
        $aiMessage = "Generate $points $type based on the category: $category, each $type should be one liner.";

        // If the user has provided custom content, append it to the AI prompt
        if (!empty($userContent)) {
            $aiMessage .= " Write about $userContent content.";
        }

        // Log the final AI message that is sent to the API
        Log::info('AI Message sent to API:', [
            'ai_message' => $aiMessage
        ]);

        // Initialize the HTTP client (Guzzle) for making the request to the AI API
        $client = new Client();

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $openaiModel,
                    'messages' => [
                        [
                            "role" => "system",
                            "content" => "You are a helpful assistant."
                        ],
                        [
                            "role" => "user",
                            "content" => $aiMessage,
                        ]
                    ],
                ],
            ]);

            // Decode the JSON response
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract the joke content from the response
            $jokeContent = $responseBody['choices'][0]['message']['content'] ?? 'No joke generated';

            // Log the full response and the generated joke content
            Log::info('API Response:', [
                'status' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => $responseBody,
                'joke_content' => $jokeContent
            ]);

            // Extract the points from the joke content (split by new lines)
            $points = preg_split('/\r\n|\r|\n/', $jokeContent);

            // Send the joke content and points back to the frontend
            return response()->json([
                'success' => true,
                'joke_content' => $jokeContent,
                'points' => $points,
            ]);

        } catch (\Exception $e) {
            // Handle any errors that occur during the API request
            Log::error('Error generating AI joke: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'There was an error generating the joke.',
            ]);
        }
    }

    public function Blog()
    {
        $title = 'All Blogs';
        $blog = DynamicPage::where('page_status', 'completed')->orderBy('id', 'desc')->get();
        $categories = DynamicPage::where('page_status', 'completed')
        ->select('category')
        ->distinct()
        ->limit(5) // Limiting to 5 categories
        ->get();
        $recents = DynamicPage::where('page_status', 'completed')->limit(5)->get();
        return view('frontend.blog1', compact('blog','title','categories','recents'));
    }
    
    public function showByCategory($category)
    {
        $blog = DynamicPage::where('category', $category)->orderBy('id', 'desc')->get();
        $title = 'Category | '.$category;
        return view('frontend.blog', compact('blog','title'));
    }

    //Image Gallery Front End Page
    public function ContactUs()
    {
        return view('frontend.contact');
    }
   
    // 
    public function StableDiffusionPage()
    {   
        $images = StableDiffusionGeneratedImage::latest()->get();
        return view('frontend.stable_diffusion_frontend', compact('images'));
    }

    //Template Front End Page
    public function FrontendFreeTemplate()
    {
        $templates = Template::orderby('id', 'asc')->get();
        $templatecategories = TemplateCategory::latest()->get();
        return view('frontend.template', compact('templates','templatecategories'));
    }

    public function TemplateView($slug)
    {
        // Find the template by slug
        $Template = Template::where('slug', $slug)->firstOrFail();

        // Convert JSON strings to arrays
        $inputTypes = json_decode($Template->input_types, true);
        $inputNames = json_decode($Template->input_names, true);
        $inputLabels = json_decode($Template->input_labels, true);

        $content = '';

        return view('frontend.template_generate', compact('Template', 'inputTypes', 'inputNames', 'inputLabels', 'content'));
    }

    // Generate Using Open AI
    public function templategenerate(Request $input)
    {
        $template_id = $input->template_id;
        $setting = AISettings::find(1);
        $template = Template::find($template_id);
        $user = Auth::user();

        $language = $input->language ?? 'English';
        $max_result_length_value = intval($input->max_result_length_value) ?? 100;
        $temperature_value = floatval($input->temperature_value) ?? 0;
        $top_p_value = floatval($input->top_p_value) ?? 1;
        $frequency_penalty_value = floatval($input->frequency_penalty_value) ?? 0;
        $presence_penalty_value = floatval($input->presence_penalty_value) ?? 0;
        $tone = $input->tone ?? 'professional';
        $creative_level = $input->creative_level ?? 'High';
        $style = $input->style ?? '';

        $prompt = $input->prompt;
        if ($input->emoji == 1) {
            $prompt .= 'Use proper emojis and write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. Do not write translations. Please make sure that the output should be within ' . $max_result_length_value . ' tokens. Consider simplifying your request or providing more specific instructions to ensure the output fits within the token limit.';
        } elseif (!empty($style)) {
            $prompt .= 'Write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. The image style should be ' . $style . '. Do not write translations. Please make sure that the output should be within ' . $max_result_length_value . ' tokens. Consider simplifying your request or providing more specific instructions to ensure the output fits within the token limit.';
        } else {
            $prompt .= 'Write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. Do not write translations. Please make sure that the output should be within ' . $max_result_length_value . ' tokens. Consider simplifying your request or providing more specific instructions to ensure the output fits within the token limit.';
        }

        foreach ($input->all() as $name => $inpVal) {
            if ($name != '_token' && $name != 'project_id' && $name != 'max_completion_tokens') {
                $name = '{' . $name . '}';
                if (!is_null($inpVal) && !is_null($name)) {
                    $prompt = str_replace($name, $inpVal, $prompt);
                } else {
                    return response()->json([
                        'status'  => 400,
                        'success' => false,
                        'message' => 'Your input does not match with the custom prompt',
                    ]);
                }
            }
        }

        $messages = 
        [
            [
                'role' => 'system', 
                'content' => 'You are a helpful assistant.'
            ],

            [
                'role' => 'user', 
                'content' => $prompt
            ],
        ];

        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);



        $result = $client->chat()->create([
            "model" => 'gpt-4o-mini',
            "temperature" => $temperature_value,
            "top_p" => $top_p_value,
            "frequency_penalty" => $frequency_penalty_value,
            "presence_penalty" => $presence_penalty_value,
            'max_completion_tokens' => $max_result_length_value,
            'messages' => $messages,
        ]);

        $completionTokens = $result->usage->completionTokens;
        $content = trim($result['choices'][0]['message']['content']);
        $char_count = strlen($content);
        $num_tokens = ceil($char_count / 4);
        $num_words = str_word_count($content);
        $num_characters = strlen($content);

    // Stream the response
    return response()->stream(function () use ($content, $num_tokens, $num_words, $num_characters, $completionTokens) {
        $chunks = explode("\n", $content); // Split the content into chunks
        
        // Stream each chunk
        foreach ($chunks as $chunk) {
            echo $chunk . "<br/>";
            ob_flush(); // Flush the output buffer
            flush();     // Flush the system output buffer
            sleep(1);    // Simulate delay between chunks (optional)
        }

        // Send the stats after content
        echo json_encode([
            'num_tokens' => $num_tokens,
            'num_words' => $num_words,
            'num_characters' => $num_characters,
            'completionTokens' => $completionTokens,
        ]);
    });
    }


    public function FrontendFreeEducation()
    {   
        $tools = EducationTools::get();
        foreach ($tools as $image) {
            $image->image = config('filesystems.disks.azure.url') 
                . config('filesystems.disks.azure.container') 
                . '/' . $image->image;
        }
        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();
       
        return view('frontend.education', compact('tools', 'categories'));
    }

    public function EducationView($slug)
    {
        // Retrieve the tool by slug
        $tool = EducationTools::where('slug', $slug)->firstOrFail();
    
         // Append full Azure URL
         $tool->image = config('filesystems.disks.azure.url') 
         . config('filesystems.disks.azure.container') 
         . '/' . $tool->image;

        $classes = GradeClass::with('subjects')->get();
        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();
    
        // Pass the tool to the view
        return view('frontend.education_tools_view_frontend', compact('tool', 'classes', 'categories'));
    }
    


    public function EducationGenerate(Request $request)
    {
        set_time_limit(0);
    
        $toolId = $request->input('tool_id');
        $tool = EducationTools::find($toolId);  // Assuming 'Tool' is your model
    
        if (!$tool) {
            return response()->json(['error' => 'Tool not found'], 404);
        }
    
        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);
    
        $savedPrompt = $tool->prompt;
    
        // Start building the final prompt by appending the saved prompt from the tool
        $prompt = $savedPrompt . " "; 
    
        // Fetch the selected grade from the `grade_id`
        if ($gradeId = $request->input('grade_id')) {
            $grade = GradeClass::find($gradeId); // Assuming your model is `Grade`
            if ($grade) {
                $prompt .= "Grade: " . $grade->grade . ". "; // Append the actual grade value
            }
        }
    
        foreach ($request->except(['_token', 'tool_id', 'grade_id']) as $key => $value) {
            if (!empty($value)) {
                $escapedValue = addslashes($value);
                $prompt .= ucfirst(str_replace('_', ' ', $key)) . ": $escapedValue. ";
            }
        }
    
        // Generate content using OpenAI API
        $response = $client->chat()->create([
            "model" => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    
         
    
        $content = $response['choices'][0]['message']['content'];
    
        // Stream the response
        return response()->stream(function () use ($content) {
            $chunks = explode("\n", $content);
            $parsedown = new Parsedown(); // Initialize Parsedown
    
            foreach ($chunks as $chunk) {
                $htmlChunk = $parsedown->text($chunk);
                echo $htmlChunk;
                ob_flush();
                flush();
                sleep(1); // Simulate delay between chunks
            }
        });
    }



    //Template Front End Page
    public function FrontendFreePromptLibrary()
    {
        $promptLibrary = PromptLibrary::where('inFrontEnd', 'yes')->get();
        // $prompt_library_category = PromptLibraryCategory::orderby('id', 'asc')->get();
        $categories = PromptLibraryCategory::latest()->get();
        // $count = $prompt_library->count(); 
        return view('frontend.prompt_library', compact('promptLibrary', 'categories'));
    }

     // Frontend
     public function PromptFrontendView($slug)
     {
         // Find the template by slug
         $prompt_library = PromptLibrary::where('slug', $slug)->firstOrFail();
         // Get the related examples
         $prompt_library_examples = $prompt_library->examples;
 
         return view('frontend.prompt_library_details_frontend', compact('prompt_library', 'prompt_library_examples'));
     }

    //All Jobs Front End Page
    public function AllJobs()
    {
        return view('frontend.job.all_jobs');
    }

    public function detailsJob($slug)
    {
        $job = Job::where('slug', $slug)->firstOrFail();
        return view('backend.job.job_detail', compact('job'));
    }

    public function JobDetails($id)
    {
        // Fetch job details from the database
        $job = Job::findOrFail($id);

        // Return job details as JSON response
        return response()->json($job);
    }


    //Privacy Policy Front End Page
    public function PrivacyPolicy()
    {
        return view('frontend.privacy_policy');
    }


    //Terms & Conditions Front End Page
    public function TermsConditions()
    {
        return view('frontend.terms_conditions');
    }


    // Mail
    public function sendEmail(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'user_message' => $request->input('comments') // Rename key to 'user_message'
        ];

        Mail::send('admin.layouts.email_test', $data, function ($mail) use ($data) {
            $mail->to('clevercreatorai@gmail.com')
                ->subject($data['subject']);
        });

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    

    public function NewsLetterStore(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:news_letters,email', // Adjust the table and column name as needed
        ]);
        
        // Attempt to retrieve user's IP address from request headers
        $ipAddress = $request->ip();

        // If the IP address is not found in the request headers, use fallback
        if ($ipAddress == '127.0.0.1') {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        }

        // Insert user's subscription details into the database
        $NewsLetter = NewsLetter::insertGetId([
            'email' => $request['email'],
            'name' => $request->name,
            'ipaddress' => $ipAddress, // Store IP address
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscribed Successfully');
    } // end method

    public function NewsLetterManage()
    {

        $newsletter = NewsLetter::orderby('id', 'asc')->get();

        // Get all user emails
        $registeredEmails = User::pluck('email')->toArray();
    
        // Group emails and count occurrences
        $groupedNewsletter = $newsletter->groupBy('email')->map(function ($group) use ($registeredEmails) {
            $email = $group->first()->email;
    
            return [
                'count' => $group->count(),
                'data' => $group,
                'isRegistered' => in_array($email, $registeredEmails), // Check in bulk
            ];
        });
    
        return view('backend.newsletter.manage_newsletter', compact('groupedNewsletter'));
    }


    // PRIVACY POLICY BACKEND
    public function ManagePrivacyPolicy()
    {
        $privacy_policy = PrivacyPolicy::orderBy('id', 'asc')->get();
        return view('backend.privacy_policy.manage_privacy_policy', compact('privacy_policy'));
    }

    public function StorePrivacyPolicy(Request $request)
    {

        $privacy_policy = PrivacyPolicy::create([
            'details' => $request->details,
            'status' => 'inactive',
        ]);

        return redirect()->back();
    }

    public function EditPrivacyPolicy($id)
    {
        $privacy_policy = PrivacyPolicy::orderBy('id', 'asc')->get();
        $privacy_policys = PrivacyPolicy::findOrFail($id);

        return view('backend.privacy_policy.edit_privacy_policy', compact('privacy_policy', 'privacy_policys'));
    }


    public function UpdatePrivacyPolicy(Request $request)
    {

        PrivacyPolicy::findOrFail($request->id)->update([
            'details' => $request->details,
        ]);

        $notification = array(
            'message' => 'Details Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect(route('manage.privacy.policy'))->with($notification);
    }

    public function togglePolicyStatus(Request $request, $id)
    {
        $policy = PrivacyPolicy::findOrFail($id);
    
        // Toggle logic
        $policy->status = $policy->status === 'active' ? 'inactive' : 'active';
        $policy->save();
    
        return response()->json([
            'status' => $policy->status,
            'message' => 'Status updated successfully'
        ]);
    }
    


    public function DeletePrivacyPolicy($id)
    {

        PrivacyPolicy::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Policy Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    } // end method



    // TERMS & CONDITIONS BACKEND
    public function ManageTermsCondition()
    {
        $terms_condition = TermsConditions::orderBy('id', 'asc')->get();
        return view('backend.terms_conditions.manage_terms_conditions', compact('terms_condition'));
    }

    public function StoreTermsCondition(Request $request)
    {

        $privacy_policy = TermsConditions::create([
            'details' => $request->details
        ]);

        return redirect()->back();
    }

    public function EditTermsCondition($id)
    {
        $terms_condition = TermsConditions::orderBy('id', 'asc')->get();
        $terms_conditions = TermsConditions::findOrFail($id);

        return view('backend.terms_conditions.edit_terms_conditions', compact('terms_condition', 'terms_conditions'));
    }


    public function UpdateTermsCondition(Request $request)
    {

        TermsConditions::findOrFail($request->id)->update([
            'details' => $request->details,
        ]);

        $notification = array(
            'message' => 'Conditions Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect(route('manage.terms.condition'))->with($notification);
    }

    public function DeleteTermsCondition($id)
    {

        TermsConditions::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Conditions Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    } // end method
}
