<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CustomTemplateCategory;
use App\Models\CustomTemplate;
use App\Models\AISettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use OpenAI;

class CustomTemplateController extends Controller
{

    // Custom Template Category
    public function CustomTemplateCategoryAdd()
    {
        $user_id = Auth::user()->id;
        $categories = CustomTemplateCategory::where('user_id', $user_id)->get();
        return view('backend.custom_template.category', compact('categories'));
    }

    public function CustomTemplateCategoryStore(Request $request)
    {
        $user_id = Auth::user()->id;

        $customTemplateCategory = CustomTemplateCategory::insertGetId([

            'user_id' => $user_id,
            'category_name' => $request->category_name,
            'category_icon' => $request->category_icon,
            'created_at' => Carbon::now(),

        ]);

        return redirect()->back()->with('success', 'Custom Template Category Saved Successfully');

    }

    public function CustomTemplateCategoryEdit($id)
    {
        $categories = CustomTemplateCategory::orderBy('id', 'ASC')->get();
        $category = CustomTemplateCategory::findOrFail($id);
        return view('backend.custom_template.category_edit', compact('category', 'categories'));
    }


    public function CustomTemplateCategoryUpdate(Request $request)
    {

        $id = $request->id;

        CustomTemplateCategory::findOrFail($id)->update([
            'category_name' => $request->category_name,
            'category_icon' => $request->category_icon,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

    } // end method 


    public function CustomTemplateCategoryDelete($id)
    {
        $category = CustomTemplateCategory::findOrFail($id);

        CustomTemplateCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('custom.template.category.add')->with($notification);
    } // end method

    // Custom Template

    public function CustomTemplateAdd()
    {
        $categories = CustomTemplateCategory::latest()->get();
        return view('backend.custom_template.template_add', compact('categories'));
    }

    public function CustomTemplateManage()
    {
        $user_id = Auth::user()->id;
        $templates = CustomTemplate::where('user_id', $user_id)->get();
        $customtemplatecategories = CustomTemplateCategory::where('user_id', $user_id)->get();
        return view('backend.custom_template.template_manage', compact('templates', 'customtemplatecategories'));
    }

    public function CustomTemplateView($id)
    {
        $customTemplate = CustomTemplate::findOrFail($id);

        // Convert JSON strings to arrays
        $inputTypes = json_decode($customTemplate->input_types, true);
        $inputNames = json_decode($customTemplate->input_names, true);
        $inputLabels = json_decode($customTemplate->input_labels, true);

        $content = '';


        return view('backend.custom_template.template_view', compact('customTemplate', 'inputTypes', 'inputNames', 'inputLabels', 'content'));
    }


    public function CustomTemplateStore(Request $request)
    {

        $user_id = Auth::user()->id;


        // Validate the incoming request
        $validatedData = $request->validate([
            'template_name' => 'required|string',
            'icon' => 'nullable|string',
            'category_id' => 'required|exists:custom_template_categories,id',
            'description' => 'nullable|string',
            'input_types' => 'required|array',
            'input_names' => 'required|array',
            'input_labels' => 'required|array',
            'prompt' => 'nullable|string',
        ]);

        $slug = Str::slug($validatedData['template_name']);

        $templateInput = new CustomTemplate();
        $templateInput->template_name = $validatedData['template_name'];
        $templateInput->user_id = $user_id;
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
    public function customtemplategenerate(Request $input)
    {

        $openaiModel = Auth::user()->selected_model;

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
                        'message' => 'Your input does not match with the custom prompt',
                    ];
                    return $data;
                }
            }
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

        $result = $client->chat()->create([
            "model" => $openaiModel,
            "temperature" => floatval($temperature_value),
            "top_p" => floatval($top_p_value),
            "frequency_penalty" => floatval($frequency_penalty_value),
            "presence_penalty" => floatval($presence_penalty_value),
            'max_tokens' => $max_result_length_value,
            'messages' => $messages,
        ]);

        $content = trim($result['choices'][0]['message']['content']);

        // return view('backend.custom_template.template_view', compact('title', 'content'));
        return $content;
    }
}
