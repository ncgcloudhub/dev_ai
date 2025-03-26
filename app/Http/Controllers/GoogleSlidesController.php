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
            return response()->json(['error' => 'Invalid token format in database.'], 400);
        }
    
        $this->client->setAccessToken($token);
    
        // Refresh the token if expired
        if ($this->client->isAccessTokenExpired()) {
            if (empty($token['refresh_token'])) {
                return response()->json(['error' => 'No refresh token available.'], 400);
            }
    
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
            $this->client->setAccessToken($newToken);
    
            $user->google_token = json_encode($newToken);
            $user->save();
        }
    
        // Fetch content from OpenAI
        $openAiResponse = $this->generateContentWithOpenAI();
        $slidesData = $openAiResponse['slides'];
    
        $slidesService = new GoogleSlides($this->client);
    
        // Create the presentation
        $presentation = new Presentation(['title' => 'AI Generated Presentation']);
        $presentation = $slidesService->presentations->create($presentation);
        $presentationId = $presentation->presentationId;
    
        // Create slide requests dynamically
        $requests = [];
        foreach ($slidesData as $index => $slide) {
            $requests[] = new SlidesRequest([
                'createSlide' => [
                    'objectId' => 'slide' . ($index + 1),
                    'insertionIndex' => $index,
                    'slideLayoutReference' => [
                        'predefinedLayout' => 'TITLE_AND_BODY'
                    ]
                ]
            ]);
        }
    
        if (empty($requests)) {
            return response()->json(['error' => 'No slides generated.'], 400);
        }
    
        // Execute batch request to create slides
        $batchUpdateRequest = new BatchUpdatePresentationRequest(['requests' => $requests]);
        $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
    
        // Get created slides
        $slides = $slidesService->presentations->get($presentationId, ['fields' => 'slides'])->getSlides();
        if (empty($slides)) {
            return response()->json(['error' => 'No slides found in the presentation.'], 400);
        }
    
        // Prepare text insertion requests
        $requests = [];
        foreach ($slidesData as $index => $slideContent) {
            if (!isset($slides[$index])) continue; // Ensure slide exists
    
            $pageElements = $slides[$index]->getPageElements();
            if (count($pageElements) < 2) continue; // Ensure title and body exist
    
            $titleObjectId = $pageElements[0]->getObjectId();
            $bodyObjectId = $pageElements[1]->getObjectId();
    
            $requests[] = new SlidesRequest([
                'insertText' => [
                    'objectId' => $titleObjectId,
                    'text' => $slideContent['title'],
                    'insertionIndex' => 0
                ]
            ]);
            $requests[] = new SlidesRequest([
                'insertText' => [
                    'objectId' => $bodyObjectId,
                    'text' => $slideContent['body'],
                    'insertionIndex' => 0
                ]
            ]);
        }
    
        if (!empty($requests)) {
            $batchUpdateRequest = new BatchUpdatePresentationRequest(['requests' => $requests]);
            $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        }
    
        return response()->json([
            'message' => 'Presentation created successfully!',
            'presentationId' => $presentationId
        ]);
    }
    
    /**
     * Fetches dynamic slide content from OpenAI
     */
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
                    ['role' => 'user', 'content' => 'Generate 2 slides with a title and a short body text for a presentation about AI advancements.']
                ],
                'temperature' => 0.7
            ]
        ]);
    
        $data = json_decode($response->getBody(), true);

        Log::info('OpenAI Response:', $data);

        $generatedText = $data['choices'][0]['message']['content'] ?? '';
    
        $slides = [];
        $lines = explode("\n", trim($generatedText));
        for ($i = 0; $i < count($lines); $i += 2) {
            if (!isset($lines[$i + 1])) break;
            $slides[] = [
                'title' => trim($lines[$i]),
                'body' => trim($lines[$i + 1])
            ];
        }
    
        return ['slides' => $slides];
    }
    

    
}
