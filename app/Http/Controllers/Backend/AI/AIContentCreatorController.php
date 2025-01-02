<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TemplateCategory;
use App\Models\Template;
use App\Models\AISettings;
use App\Models\NewsLetter;
use App\Models\RatingTemplate;
use App\Models\Referral;
use App\Models\RequestModuleFeedback;
use App\Models\SectionDesign;
use App\Models\TemplateGeneratedContent;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenAI;
use Stevebauman\Location\Facades\Location;
use GuzzleHttp\Client;

class AIContentCreatorController extends Controller
{

    public function updateDesign(Request $request)
{
    // Update 'how_it_works' section if its form is submitted
    if ($request->has('how_it_works')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'how_it_works'],
            ['selected_design' => $request->input('how_it_works_design')]
        );
    }

    // Update 'banner' section if its form is submitted
    if ($request->has('banner')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'banner'],
            ['selected_design' => $request->input('banner_design')]
        );
    }

     // Update 'features' section if its form is submitted
     if ($request->has('features')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'features'],
            ['selected_design' => $request->input('features_design')]
        );
    }

     // Update 'services' section if its form is submitted
     if ($request->has('services')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'services'],
            ['selected_design' => $request->input('services_design')]
        );
    }

     // Update 'image_generate' section if its form is submitted
     if ($request->has('image_generate')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'image_generate'],
            ['selected_design' => $request->input('image_generate_design')]
        );
    }

    // Update 'image_slider' section if its form is submitted
    if ($request->has('image_slider')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'image_slider'],
            ['selected_design' => $request->input('image_slider_design')]
        );
    }

    // Update 'image_gallery' section if its form is submitted
    if ($request->has('image_gallery')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'image_gallery'],
            ['selected_design' => $request->input('image_gallery_design')]
        );
    }

    // Update 'content_creator' section if its form is submitted
    if ($request->has('content_creator')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'content_creator'],
            ['selected_design' => $request->input('content_creator_design')]
        );
    }

    // Update 'prompt_library' section if its form is submitted
    if ($request->has('prompt_library')) {
        SectionDesign::updateOrCreate(
            ['section_name' => 'prompt_library'],
            ['selected_design' => $request->input('prompt_library_design')]
        );
    }

    return redirect()->back()->with('success', 'Design updated successfully.');
}


    public function getDesign()
    {
        $sectionDesigns = SectionDesign::get()->keyBy('section_name');
        return view('backend.designs.design_select_form', compact('sectionDesigns'));
    }



    // Custom Template Category
    public function AIContentCreatorCategoryAdd()
    {
        $categories = TemplateCategory::orderBy('id', 'ASC')->get();
        return view('backend.ai_content_creator.category', compact('categories'));
    }

    public function AIContentCreatorCategoryStore(Request $request)
    {

        flash()->success('Operation completed successfully.');

        $TemplateCategory = TemplateCategory::insertGetId([

            'category_name' => $request->category_name,
            'category_icon' => $request->category_icon,
            'created_at' => Carbon::now(),

        ]);

        return redirect()->back()->with('success', 'Template Saved Successfully');
    }

    public function AIContentCreatorCategoryEdit($id)
    {
        $categories = TemplateCategory::orderBy('id', 'ASC')->get();
        $category = TemplateCategory::findOrFail($id);
        return view('backend.ai_content_creator.category_edit', compact('category', 'categories'));
    }


    public function AIContentCreatorCategoryUpdate(Request $request)
    {

        $id = $request->id;

        TemplateCategory::findOrFail($id)->update([
            'category_name' => $request->category_name,
            'category_icon' => $request->category_icon,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 


    public function AIContentCreatorCategoryDelete($id)
    {
        $category = TemplateCategory::findOrFail($id);

        TemplateCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('aicontentcreator.category.add')->with($notification);
    } // end method


    // Custom Template

    public function AIContentCreatorAdd()
    {
        $categories = TemplateCategory::latest()->get();
        return view('backend.ai_content_creator.aicontentcreator_add', compact('categories'));
    }

    public function AIContentCreatorStore(Request $request)
    {

        // Validate the incoming request
        $validatedData = $request->validate([
            'template_name' => 'required|string',
            'icon' => 'nullable|string',
            'category_id' => 'required|exists:template_categories,id',
            'description' => 'nullable|string',
            'input_types' => 'required|array',
            'input_names' => 'required|array',
            'input_labels' => 'required|array',
            'input_placeholders' => 'required|array',
            'prompt' => 'nullable|string',
        ]);

        $slug = Str::slug($validatedData['template_name']);

        $templateInput = new Template();
        $templateInput->template_name = $validatedData['template_name'];
        $templateInput->slug = $slug;
        $templateInput->icon = $validatedData['icon'];
        $templateInput->category_id = $validatedData['category_id'];
        $templateInput->description = $validatedData['description'];
        $templateInput->input_types = json_encode($validatedData['input_types']);
        $templateInput->input_names = json_encode($validatedData['input_names']);
        $templateInput->input_labels = json_encode($validatedData['input_labels']);
        $templateInput->input_placeholders = json_encode($validatedData['input_placeholders']);
        $templateInput->prompt = $validatedData['prompt'];
        $templateInput->total_word_generated = '0';
        $templateInput->blog_link = $request->blog_link;
        $templateInput->video_link = $request->video_link;

        // Save the TemplateInput instance
        $templateInput->save();


        $notification = array(
            'message' => 'Settings Changed Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function AIContentCreatorEdit($slug)
    {
        $categories = TemplateCategory::orderBy('id', 'ASC')->get();
        $template = Template::where('slug', $slug)->firstOrFail();

        $templateInputs = json_decode($template->input_types, true);
        $inputNames = json_decode($template->input_names, true);
        $inputLabels = json_decode($template->input_labels, true);
        $inputPlaceholders = json_decode($template->input_placeholders, true);

        $templateInputsArray = [];
        foreach ($templateInputs as $index => $type) {
            $templateInputsArray[] = [
                'type' => $type,
                'name' => $inputNames[$index] ?? '',
                'label' => $inputLabels[$index] ?? '',
                'placeholder' => $inputPlaceholders[$index] ?? '',
            ];
        }
        return view('backend.ai_content_creator.aicontentcreator_edit', compact('template', 'categories', 'templateInputsArray'));
    }


    public function AIContentCreatorUpdate(Request $request)
    {

        $id = $request->id;

        $validatedData = $request->validate([
            'template_name' => 'required|string',
            'icon' => 'nullable|string',
            'category_id' => 'required|exists:template_categories,id',
            'description' => 'nullable|string',
            'input_types' => 'required|array',
            'input_names' => 'required|array',
            'input_labels' => 'required|array',
            'input_placeholders' => 'required|array',
            'prompt' => 'nullable|string',
        ]);

        $template = Template::findOrFail($id);
        $template->template_name = $validatedData['template_name'];
        $template->icon = $validatedData['icon'];
        $template->category_id = $validatedData['category_id'];
        $template->description = $validatedData['description'];
        $template->input_types = json_encode($validatedData['input_types']);
        $template->input_names = json_encode($validatedData['input_names']);
        $template->input_labels = json_encode($validatedData['input_labels']);
        $template->input_placeholders = json_encode($validatedData['input_placeholders']);
        $template->prompt = $validatedData['prompt'];
        $template->blog_link = $request->blog_link;
        $template->video_link = $request->video_link;
        $template->inFrontEnd = $request->inFrontEnd;
        $template->save();

        return redirect()->back()->with('success', 'Template updated successfully');
    } // end method
    
    public function AIContentCreatorDelete($id)
    {
        $aiContentCreator = Template::findOrFail($id);

        $aiContentCreator->delete();

        $notification = array(
            'message' => 'AI Content Creator Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AIContentCreatorManage()
    {
        $templates = Template::orderby('id', 'asc')->get();
        $templatecategories = TemplateCategory::latest()->get();
        $userRatings = [];
        $userFeedbacks = [];

        if (auth()->check()) {
            $userRatings = RatingTemplate::where('user_id', auth()->id())
                ->pluck('rating', 'template_id')
                ->toArray();

                // Fetch feedbacks related to the user
                $userRequestFeedbacks = RequestModuleFeedback::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

                $hasPendingFeedback = RequestModuleFeedback::where('user_id', auth()->id())
                ->where('status', 'Pending')
                ->exists();
        }

        return view('backend.ai_content_creator.aicontentcreator_manage', compact('templates', 'templatecategories', 'userRatings','userRequestFeedbacks','hasPendingFeedback'));
    }

    public function AIContentCreatorView($slug)
    {
        // Find the template by slug
        $Template = Template::where('slug', $slug)->firstOrFail();

        // Convert JSON strings to arrays
        $inputTypes = json_decode($Template->input_types, true);
        $inputNames = json_decode($Template->input_names, true);
        $inputLabels = json_decode($Template->input_labels, true);
        $inputPlaceholders = json_decode($Template->input_placeholders, true);

        $content = '';

        return view('backend.ai_content_creator.aicontentcreator_view', compact('Template', 'inputTypes', 'inputNames', 'inputLabels', 'inputPlaceholders', 'content'));
    }

    // Extract Image from Prompt
    public function AIContentCreatorExtractPromptAndGenerate()
    {
        $apiKey = config('services.stable_diffusion.api_key');
        return view('backend.ai_content_creator.aicontentcreator_image_generate_view',compact('apiKey'));
    }

    public function AIContentCreatorSEOUpdate(Request $request)
    {
        $id = $request->id;

        $validatedData = $request->validate([
            'page_title' => 'nullable|string',          
            'page_description' => 'nullable|string', 
            'page_tagging' => 'nullable|string', 
        ]);

        $template = Template::findOrFail($id);
      
        $template->page_title = $validatedData['page_title'] ?? null;
        $template->page_description = $validatedData['page_description'] ?? null;
        $template->page_tagging = $validatedData['page_tagging'] ?? null;        
        $template->save();

        return redirect()->back()->with('success', 'Template SEO updated successfully');
    } // end method 


    // SEO AUTO FILL with AI
    public function fetchTemplate(Request $request, $id)
    { 
        // Fetch the template details by ID
        $template = Template::find($id);
        $template_name = $template->template_name;
        $description = $template->description;
       
        $user = auth()->user();
        $openaiModel = $user->selected_model;

        $client = new Client();
              $response = $client->post('https://api.openai.com/v1/chat/completions', [
                  'headers' => [
                      'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                      'Content-Type' => 'application/json',
                  ],
                  'json' => [
                      'model' => $openaiModel,
                      'messages' => [  // Corrected here
                         [
                            "role" => "system", 
                            "content" => "You are an SEO assistant."
                        ],
                        [
                            "role" => "user", 
                            "content" => "Generate an SEO title not more than 60 characters, description not more than 160 characters and relevant 20 tags which are comma separated for $template_name and to describe it is $description. The header for the Title should be '**SEO Title:**', the header for Description should be '**SEO Description:**', and the header for the Tags should be '**Relevant Tags:**'"
                        ]
                    ],
                  ],
              ]);

            // Decode the response to get a more readable format
            $responseBody = json_decode($response->getBody()->getContents(), true);

            $totalTokens = $responseBody['usage']['total_tokens'];
            deductUserTokensAndCredits($totalTokens);

            // Log the entire raw API response for debugging
            Log::info('OpenAI API Full Response: ', ['response' => $responseBody]);

            // Correctly extract the assistant's response content
            $assistantContent = $responseBody['choices'][0]['message']['content'] ?? 'No content generated';

            // Log the raw content generated by the assistant
            Log::info('Assistant Content: ', ['content' => $assistantContent]);

            // Update the regex to correctly match the title and description
            preg_match('/\*\*SEO Title:\*\*\s*(.*)/', $assistantContent, $titleMatches);
            preg_match('/\*\*SEO Description:\*\*\s*(.*)/', $assistantContent, $descriptionMatches);

            // For tags, match the tag section after "Relevant Tags"
            preg_match('/\*\*Relevant Tags:\*\*\s*(.*)$/s', $assistantContent, $tagsMatches);

            // Get the title, description, and tags if matches are found
            $seoTitle = $titleMatches[1] ?? 'No title generated';
            $seoDescription = $descriptionMatches[1] ?? 'No description generated';

            // For tags, keep them as a comma-separated string without trimming spaces
            $seoTags = $tagsMatches[1] ?? 'No tags generated';

            // Log the parsed title, description, and tags for debugging
            Log::info('Parsed SEO Title: ', ['title' => $seoTitle]);
            Log::info('Parsed SEO Description: ', ['description' => $seoDescription]);
            Log::info('Parsed SEO Tags: ', ['tags' => $seoTags]);



                // Return the response with title, description, and tags
                return response()->json([
                    'success' => true,
                    'seo_title' => $seoTitle,
                    'seo_description' => $seoDescription,
                    'seo_tags' => $seoTags,
                ]);
    }


    // Generate Using Open AI
    public function AIContentCreatorgenerate(Request $input)
    {
        $template_id = $input->template_id;
        // Get the currently authenticated user
        $user = auth()->user();
        // Retrieve the selected model from the `selected_model` field
        $openaiModel = $user->selected_model;
        Log::info('before: ' . $openaiModel);
        $template = Template::find($template_id);
        $user = Auth::user();

        $points = 1;
        $language = 'English';

        $max_result_length_value = 100;
        $temperature_value = 0;
        $top_p_value = 1;
        $frequency_penalty_value = 0;
        $presence_penalty_value = 0;
        $tone = 'professional';
        $creative_level = 'High';


        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);


        if ($input->points != NULL) {
            $points = $input->points;
        }

        if ($input->language != NULL) {
            $language = $input->language;
        }

        if ($input->max_result_length_value != NULL) {
            $max_result_length_value = intval($input->max_result_length_value);
        }

        if ($input->temperature_value != NULL) {
            $temperature_value = $input->temperature_value;
        }

        if ($input->top_p_value != NULL) {
            $top_p_value = $input->top_p_value;
        }

        if ($input->frequency_penalty_value != NULL) {
            $frequency_penalty_value = $input->frequency_penalty_value;
        }

        if ($input->presence_penalty_value != NULL) {
            $presence_penalty_value = $input->presence_penalty_value;
        }

        if ($input->tone != NULL) {
            $tone = $input->tone;
        }

        if ($input->creative_level != NULL) {
            $creative_level = $input->creative_level;
        }

        if ($input->style != NULL) {
            $style = $input->style;
        }

        $prompt =  $input->prompt;

        if ($input->emoji == 1) {
            $prompt .= 'Use proper emojis and write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. Write '. $points . ' points about it. Do not write translations. ';
        } elseif (isset($input->style) && $input->style != "") {
            $prompt .= 'Write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. The image style should be ' . $style . '. Write '. $points . ' points about it. Do not write translations. ';
        } else {
            $prompt .= 'Write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. Write '. $points . ' points about it. Do not write translations. ';
        }


        foreach ($input->all() as $name => $inpVal) {
            if ($name != '_token' && $name != 'project_id' && $name != 'max_tokens') {
                $name = '{' . $name . '}';
                if (!is_null($inpVal) && !is_null($name)) {
                    $prompt = str_replace($name, $inpVal, $prompt);
                } else {
                    $data = [
                        'status'  => 400,
                        'success' => false,
                        'message' => 'Your input does not match with the custom prompt',
                        'message' => 'Your input does not match with the custom prompt',
                    ];
                    return $data;
                }
            }
        }

        if ($user->tokens_left <= 0) {
            $data = 0;
            return $data;
        }

        // Estimate the number of tokens in the prompt
        $promptTokens = ceil(strlen($prompt) / 4); // Average token length is around 4 characters
        $maxTokens = min($max_result_length_value, 8192 - $promptTokens); // Ensure total tokens do not exceed 8192

        if ($promptTokens + $maxTokens > 8192) {
            return response()->json(['error' => 'The prompt is too long. Please reduce the prompt length.'], 400);
        }

        // Prepare messages for chat completions endpoint
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

        // Initialize the OpenAI client and request parameters
    $result = $client->chat()->create([
        "model" => $openaiModel,
        "temperature" => floatval($temperature_value),
        "top_p" => floatval($top_p_value),
        "frequency_penalty" => floatval($frequency_penalty_value),
        "presence_penalty" => floatval($presence_penalty_value),
        'max_tokens' => $maxTokens,
        'messages' => $messages,
    ]);

    $completionTokens = $result->usage->completionTokens;
    $totalTokens = $result->usage->totalTokens;

    // Process the response
    $content = trim($result['choices'][0]['message']['content']);
    $char_count = strlen($content);
    $num_tokens = ceil($char_count / 4);
    $num_words = str_word_count($content);
    $num_characters = strlen($content);

    if ($user->tokens_left <= 0) {
        return response()->json(0);
    } else {
        deductUserTokensAndCredits($totalTokens);

        Template::where('id', $template->id)->update([
            'total_word_generated' => DB::raw('total_word_generated + ' . $num_words),
        ]);

        // Limit the saved generated contents per template
        $userGeneratedContentsCount = TemplateGeneratedContent::where('user_id', $user->id)
            ->where('template_id', $template_id)
            ->count();

        if ($userGeneratedContentsCount >= 3) {
            TemplateGeneratedContent::where('user_id', $user->id)
                ->where('template_id', $template_id)
                ->orderBy('created_at', 'asc')
                ->first()
                ->delete();
        }

        // Save the new entry
        TemplateGeneratedContent::create([
            'user_id' => $user->id,
            'template_id' => $template_id,
            'prompt' => $prompt,
            'generated_content' => $content,
        ]);

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
    }


    // GOOGLE SOCIALITE
    public function provider()
    {
        return Socialite::driver('google')->redirect();
    }


    public function callbackHandel(Request $request)
    {

        // Log session state and request state
        Log::info('Session state: ' . session('state'));
        Log::info('Request state: ' . $request->input('state'));

        // Check if an error occurred during the authentication process
        if (request()->has('error') && request()->error == 'access_denied') {
            // Handle the error gracefully, such as redirecting back to the login page
            return redirect('/login')->with('error', 'Google authentication was canceled.');
        }

        try {
            // Get user data from Google
            $googleUser = Socialite::driver('google')->user();
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Invalid state exception: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Invalid state.');
        }

        // Check if the user exists in your database
        $user = User::where('email', $googleUser->email)->first();

        // Attempt to retrieve user's IP address from request headers
        $ipAddress = $request->ip();

         // Retrieve user's location based on IP address
         $location = Location::get($ipAddress);
         if ($location) {
            // Safely access properties if location is successfully retrieved
            $regionAndCountry = $location->regionName . ', ' . $location->countryName;
        } else {
            // Handle the case where location retrieval failed
            $regionAndCountry = 'Location not found';
        }


        // If the user doesn't exist, create a new user
        if (is_null($user)) {
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'status' => 'active',
                'credits_left' => 100,
                'tokens_left' => 5000,
                'role' => 'user', // You might want to adjust the role here
                'password' => '', // Since this is a social login, you don't need a password
                'ipaddress' => $ipAddress, // Store IP address
                'email_verified_at' => now(),
                'country' => $regionAndCountry,
            ]);

            // Generate a referral link for the new user
            $newUser->referral_link = route('register', ['ref' => $newUser->id]);
            $newUser->save();

            // Check if there's a referrer ID in the query parameters
            if ($request->ref) {
                // Extract the referrer ID from the query parameters
                $referrerId = $request->ref;

                // Store referral information in the database
                Referral::create([
                    'referrer_id' => $referrerId,
                    'referral_id' => $newUser->id,
                    'status' => 'pending',
                ]);
            }

            // Populate the NewsLetter model
            NewsLetter::create([
                'email' => $newUser->email,
                'ipaddress' => $ipAddress,
            ]);

            // Log in the new user
            Auth::login($newUser);
            // Redirect to dashboard or any other page
            return redirect('/chat');
        }

        // If the user exists, log them in
        Auth::login($user);
        // Redirect to dashboard or any other page
        return redirect('/chat');
    }


    //   GITHUB
    public function githubprovider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubcallbackHandel(Request $request)
    {
        // Get user data from Google
        $githubUser = Socialite::driver('github')->user();
        // dd($githubUser);
        // Check if the user exists in your database
        $user = User::where('email', $githubUser->email)->first();
        $name = $githubUser->name ?? $githubUser->nickname ?? $githubUser->email;

        // Attempt to retrieve user's IP address from request headers
        $ipAddress = $request->ip();

        // Retrieve user's location based on IP address
        $location = Location::get($ipAddress);

        if ($location) {
            // Safely access properties if location is successfully retrieved
            $regionAndCountry = $location->regionName . ', ' . $location->countryName;
        } else {
            // Handle the case where location retrieval failed
            $regionAndCountry = 'Location not found';
        }


        // If the user doesn't exist, create a new user
        if (is_null($user)) {
            $newUser = User::create([
                'name' => $name,
                'email' => $githubUser->email,
                'status' => 'active',
                'credits_left' => 100,
                'tokens_left' => 5000,
                'role' => 'user', // You might want to adjust the role here
                'password' => '', // Since this is a social login, you don't need a password
                'ipaddress' => $ipAddress, // Store IP address
                'country' => $regionAndCountry,
            ]);

            // Populate the NewsLetter model
            NewsLetter::create([
                'email' => $newUser->email,
                'ipaddress' => $ipAddress,
            ]);

            // Log in the new user
            Auth::login($newUser);

            // Redirect to dashboard or any other page
            return redirect('/chat');
        }

        // If the user exists, log them in
        Auth::login($user);

        // Redirect to dashboard or any other page
        return redirect('/chat');
    }

    // Get Generated Content by User
    public function getTemplateContent($id)
    {
        $userId = auth()->id(); // Get the logged-in user ID
    
        $template = Template::find($id);
        // Fetch all matching records for the given template and user
        $contents = TemplateGeneratedContent::where('template_id', $id)
            ->where('user_id', $userId)
            ->get();
    
        // If no records are found, return an error response
        if ($contents->isEmpty()) {
            return response()->json(['error' => 'No content found for this template or access denied'], 404);
        }
    
        // Return the list of generated content
        return response()->json([
            'template_name' => $template->template_name, // Optional: Provide template details if needed
            'content_list' => $contents->map(function ($content) {
                return [
                    'id' => $content->id,
                    'created_at' => $content->created_at->format('jS F y'),
                    'generated_content' => $content->generated_content,
                ];
            }),
        ]);
    }

}
