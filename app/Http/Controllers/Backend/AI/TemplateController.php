<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TemplateCategory;
use App\Models\Template;
use App\Models\AISettings;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;


use OpenAI;

class TemplateController extends Controller
{
    // Custom Template Category
    public function TemplateCategoryAdd(){
        $categories = TemplateCategory::latest()->get();
        return view('backend.template.category', compact('categories'));
    }

    public function TemplateCategoryStore (Request $request){


        $TemplateCategory = TemplateCategory::insertGetId([
            
          'category_name' => $request->category_name,
          'category_icon' => $request->category_icon,
          'created_at' => Carbon::now(),   
  
        ]);
  
  
         $notification = array(
              'message' => 'Settings Changed Successfully',
              'alert-type' => 'success'
          );
  
          return redirect()->back()->with($notification);
  
      }

// Custom Template

public function TemplateAdd(){
    $categories = TemplateCategory::latest()->get();
    return view('backend.template.template_add', compact('categories'));
}

public function TemplateManage(){
    $templates = Template::orderby('id', 'asc')->get();
    $templatecategories = TemplateCategory::latest()->get();
    return view('backend.template.template_manage', compact('templates','templatecategories'));
}

public function TemplateView($id) {
    $Template = Template::findOrFail($id);

    // Convert JSON strings to arrays
    $inputTypes = json_decode($Template->input_types, true);
    $inputNames = json_decode($Template->input_names, true);
    $inputLabels = json_decode($Template->input_labels, true);

    $content = '';
    

    return view('backend.template.template_view', compact('Template', 'inputTypes', 'inputNames', 'inputLabels', 'content'));
}


public function TemplateStore (Request $request){

    // Validate the incoming request
    $validatedData = $request->validate([
        'template_name' => 'required|string',
        'icon' => 'nullable|string',
        'category_id' => 'required|exists:template_categories,id',
        'description' => 'nullable|string',
        'input_types' => 'required|array',
        'input_names' => 'required|array',
        'input_labels' => 'required|array',
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
    $templateInput->prompt = $validatedData['prompt'];
    $templateInput->total_word_generated = '0';

    // Save the TemplateInput instance
    $templateInput->save();


     $notification = array(
          'message' => 'Settings Changed Successfully',
          'alert-type' => 'success'
      );

      return redirect()->back()->with($notification);

  }

// Generate Using Open AI
  public function templategenerate(Request $input)
	{

        $setting = AISettings::find(1);
		

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
		

		if($input->language != NULL){
			$language = $input->language; 
		}

		if($input->max_result_length_value != NULL){
			$max_result_length_value = intval($input->max_result_length_value); 
		}

		if($input->temperature_value != NULL){
			$temperature_value = $input->temperature_value; 
		}

		if($input->top_p_value != NULL){
			$top_p_value = $input->top_p_value; 
		}

		if($input->frequency_penalty_value != NULL){
			$frequency_penalty_value = $input->frequency_penalty_value; 
		}

		if($input->presence_penalty_value != NULL){
			$presence_penalty_value = $input->presence_penalty_value; 
		}

		if($input->tone != NULL){
			$tone = $input->tone; 
		}

		if($input->creative_level != NULL){
			$creative_level = $input->creative_level; 
		}
		

		$prompt =  $input->prompt;

		$prompt .= 'Write in ' . $language . ' language. Creativity level should be ' . $creative_level . '. The tone of voice should be ' . $tone . '. Do not write translations.';


		foreach ($input->all() as $name => $inpVal) {
            if ($name != '_token' && $name != 'project_id' && $name != 'max_tokens') {
                $name = '{' . $name . '}';
                if (!is_null($inpVal) && !is_null($name)) {
                    $prompt = str_replace($name, $inpVal, $prompt);
                } else {
                    $data = [
                        'status'  => 400,
                        'success' => false,
                        'message' => localize('Your input does not match with the custom prompt'),
                    ];
                    return $data;
                }
            }
        } 
       
		$result = $client->completions()->create([
			"model" => $setting->openaimodel,
			"temperature" => floatval($temperature_value),
			"top_p" => floatval($top_p_value),
			"frequency_penalty" => floatval($frequency_penalty_value),
			"presence_penalty" => floatval($presence_penalty_value),
			'max_tokens' => $max_result_length_value,
			'prompt' => $prompt,
		]);
	
		$content = trim($result['choices'][0]['text']);

		// return view('backend.template.template_view', compact('title', 'content'));
		return $content;
	}



    // GOOGLE SOCIALITE
    public function provider ()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callbackHandel()
    {
          // Get user data from Google
    $googleUser = Socialite::driver('google')->user();

    // Check if the user exists in your database
    $user = User::where('email', $googleUser->email)->first();

    // If the user doesn't exist, create a new user
    if (is_null($user)) {
        $newUser = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'role' => 'user', // You might want to adjust the role here
            'password' => '', // Since this is a social login, you don't need a password
        ]);

        // Log in the new user
        Auth::login($newUser);

        // Redirect to dashboard or any other page
        return redirect('/user/dashboard');
    }

    // If the user exists, log them in
    Auth::login($user);

    // Redirect to dashboard or any other page
    return redirect('/user/dashboard');
  }



//   GITHUB
public function githubprovider()
{
    return Socialite::driver('github')->redirect();
}

public function githubcallbackHandel()
    {
          // Get user data from Google
    $githubUser = Socialite::driver('github')->user();
        // dd($githubUser);
    // Check if the user exists in your database
    $user = User::where('email', $githubUser->email)->first();
    $name = $githubUser->name ?? $githubUser->nickname ?? $githubUser->email;
    // If the user doesn't exist, create a new user
    if (is_null($user)) {
        $newUser = User::create([
            'name' => $name,
            'email' => $githubUser->email,
            'role' => 'user', // You might want to adjust the role here
            'password' => '', // Since this is a social login, you don't need a password
        ]);

        // Log in the new user
        Auth::login($newUser);

        // Redirect to dashboard or any other page
        return redirect('/user/dashboard');
    }

    // If the user exists, log them in
    Auth::login($user);

    // Redirect to dashboard or any other page
    return redirect('/user/dashboard');
  }
}