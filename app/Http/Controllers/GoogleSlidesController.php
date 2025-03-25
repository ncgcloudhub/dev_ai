<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Slides as GoogleSlides;
use Google\Service\Slides\Presentation;
use Google\Service\Slides\Request as SlidesRequest;
use Google\Service\Slides\BatchUpdatePresentationRequest;
use Illuminate\Support\Facades\Log;

class GoogleSlidesController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setAuthConfig(storage_path('app/credentials.json'));
        $this->client->addScope(GoogleSlides::PRESENTATIONS);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function createSlide(Request $request)
    {
        $user = $request->user();
        $token = json_decode($user->google_token, true);
    
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($token)) {
            throw new \Exception('Invalid token format in database.');
        }
    
        $this->client->setAccessToken($token);
    
        if ($this->client->isAccessTokenExpired()) {
            if (empty($token['refresh_token'])) {
                throw new \Exception('No refresh token available.');
            }
    
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
            $this->client->setAccessToken($newToken);
    
            $user->google_token = json_encode($newToken);
            $user->save();
        }
    
        // Fetch content from OpenAI
        $openAiResponse = $this->generateContentWithOpenAI();
        $titleText = $openAiResponse['title'] ?? 'AI-Generated Title';
        $bodyText = $openAiResponse['body'] ?? 'AI-Generated Body Text';
    
        $slidesService = new GoogleSlides($this->client);
    
        $presentation = new Presentation([
            'title' => 'AI Generated Presentation'
        ]);
        $presentation = $slidesService->presentations->create($presentation);
    
        // Slide Requests
        $requests = [
            // Create First Slide (Title Slide)
            new SlidesRequest([
                'createSlide' => [
                    'objectId' => 'slide1',
                    'insertionIndex' => 0,
                    'slideLayoutReference' => [
                        'predefinedLayout' => 'TITLE'
                    ]
                ]
            ]),
    
            // Create Second Slide (Content Slide)
            new SlidesRequest([
                'createSlide' => [
                    'objectId' => 'slide2',
                    'insertionIndex' => 1,
                    'slideLayoutReference' => [
                        'predefinedLayout' => 'TITLE_AND_BODY'
                    ]
                ]
            ])
        ];
    
        $batchUpdateRequest = new BatchUpdatePresentationRequest([
            'requests' => $requests
        ]);
    
        $response = $slidesService->presentations->batchUpdate($presentation->presentationId, $batchUpdateRequest);
    
        $slides = $slidesService->presentations->get($presentation->presentationId, ['fields' => 'slides'])->getSlides();
    
        // First Slide (Title Slide)
        $firstSlide = $slides[0];
        $titleObjectId = $firstSlide->getPageElements()[0]->getObjectId();
        $subtitleObjectId = $firstSlide->getPageElements()[1]->getObjectId();
    
        // Second Slide (Content Slide)
        $secondSlide = $slides[1];
        $contentTitleObjectId = $secondSlide->getPageElements()[0]->getObjectId();
        $contentBodyObjectId = $secondSlide->getPageElements()[1]->getObjectId();
    
        $requests = [
            // First Slide: Insert Title
            new SlidesRequest([
                'insertText' => [
                    'objectId' => $titleObjectId,
                    'text' => 'Welcome to the AI Presentation',
                    'insertionIndex' => 0
                ]
            ]),
            // First Slide: Insert Subtitle
            new SlidesRequest([
                'insertText' => [
                    'objectId' => $subtitleObjectId,
                    'text' => 'Generated dynamically using AI',
                    'insertionIndex' => 0
                ]
            ]),
        
             // Second Slide: Insert AI-Generated Title
            new SlidesRequest([
                'insertText' => [
                    'objectId' => $contentTitleObjectId, // Ensure this is the correct object ID
                    'text' => $titleText,
                    'insertionIndex' => 0
                ]
            ]),

            // Second Slide: Insert AI-Generated Body Content
            new SlidesRequest([
                'insertText' => [
                    'objectId' => $contentBodyObjectId, // Ensure this is the correct object ID
                    'text' => $bodyText,
                    'insertionIndex' => 0
                ]
            ])
        ];
        
        $batchUpdateRequest = new BatchUpdatePresentationRequest([
            'requests' => $requests
        ]);
        
        $slidesService->presentations->batchUpdate($presentation->presentationId, $batchUpdateRequest);
        
    
        return response()->json([
            'message' => 'Presentation created successfully!',
            'presentationId' => $presentation->presentationId
        ]);
    }
    

    private function generateContentWithOpenAI()
    {
        $apiKey = env('OPENAI_API_KEY');

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that generates slide content.'],
                    ['role' => 'user', 'content' => 'Generate a title and a short body text for a presentation about AI advancements.']
                ],
                'temperature' => 0.7
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $generatedText = $data['choices'][0]['message']['content'] ?? '';

        $splitText = explode("\n", $generatedText, 2);
        return [
            'title' => trim($splitText[0]) ?? 'AI-Generated Title',
            'body' => trim($splitText[1]) ?? 'AI-Generated Body Text'
        ];
    }

    
}
