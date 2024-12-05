<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AISettings;
use Illuminate\Http\Request;
use App\Models\DalleImageGenerate;
use App\Models\FavoriteImageDalle;
use App\Models\Job;
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
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use OpenAI;

class HomeController extends Controller
{
    // Image Gallery Front End Page
    public function AIImageGallery(Request $request)
    {
        $query = DalleImageGenerate::withCount(['likes', 'favorites']);
        $stableImages = StableDiffusionGeneratedImage::withCount('stableDiffusionLike')->get();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereRaw('LOWER(prompt) LIKE ?', ['%' . strtolower($search) . '%']);
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
    
        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
            $image->liked_by_user = LikedImagesDalle::where('user_id', Auth::id())->where('image_id', $image->id)->exists();
            $image->favorited_by_user = FavoriteImageDalle::where('user_id', Auth::id())->where('image_id', $image->id)->exists();
        }
    
        if ($request->ajax()) {
            return view('frontend.image_gallery_partial', compact('images'))->render();
        }
    
        return view('frontend.ai_image_gallery', compact('images','stableImages'));
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
            if ($name != '_token' && $name != 'project_id' && $name != 'max_tokens') {
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

        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);



        $result = $client->completions()->create([
            "model" => 'gpt-3.5-turbo-instruct',
            "temperature" => $temperature_value,
            "top_p" => $top_p_value,
            "frequency_penalty" => $frequency_penalty_value,
            "presence_penalty" => $presence_penalty_value,
            'max_tokens' => $max_result_length_value,
            'prompt' => $prompt,
        ]);

        $completionTokens = $result->usage->completionTokens;
        $content = trim($result['choices'][0]['text']);
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

    //Template Front End Page
    public function FrontendFreePromptLibrary()
    {
        $promptLibrary = PromptLibrary::where('inFrontEnd', 'yes')->get();
        // $prompt_library_category = PromptLibraryCategory::orderby('id', 'asc')->get();
        $categories = PromptLibraryCategory::latest()->get();
        // $count = $prompt_library->count(); 
        return view('frontend.prompt_library', compact('promptLibrary', 'categories'));
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
            'message' => $request->input('comments')
        ];

        Mail::send('admin.layouts.email_test', $data, function ($message) use ($data) {
            $message->to('clevercreatorai@gmail.com')
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
            'details' => $request->details
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
