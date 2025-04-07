<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Slides as GoogleSlides;
use Google\Service\Slides\Presentation;
use Google\Service\Slides\Request as SlidesRequest;
use Google\Service\Slides\BatchUpdatePresentationRequest;
use Illuminate\Support\Facades\Log;
use OpenAI;

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

    // Validate token format
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($token) || empty($token['access_token'])) {
        return response()->json(['error' => 'Invalid or missing token.'], 400);
    }

    $this->client->setAccessToken($token);

    // Refresh token if expired
    if ($this->client->isAccessTokenExpired()) {
        if (empty($token['refresh_token'])) {
            return response()->json(['error' => 'No refresh token available. Please reauthorize Google access.'], 401);
        }

        $newToken = $this->client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
        
        if (isset($newToken['access_token'])) {
            $this->client->setAccessToken($newToken);
            $user->google_token = json_encode($newToken);
            $user->save();
        } else {
            return response()->json(['error' => 'Failed to refresh token.'], 400);
        }
    }

    // Fetch content from OpenAI
    $openAiResponse = $this->generateContentWithOpenAI();
    
    if (empty($openAiResponse) || empty($openAiResponse['slides'])) {
        return response()->json(['error' => 'Failed to generate slides from OpenAI.'], 400);
    }

    $slidesData = $this->formatSlidesData($openAiResponse['slides']); // Format slides content

    if (empty($slidesData)) {
        return response()->json(['error' => 'No valid slide content generated.'], 400);
    }

    $slidesService = new GoogleSlides($this->client);

    // Create new presentation
    $presentation = new Presentation(['title' => 'AI Generated Presentation']);
    $presentation = $slidesService->presentations->create($presentation);
    $presentationId = $presentation->presentationId;

    // Create slides dynamically
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

    // Batch update request to create slides
    $batchUpdateRequest = new BatchUpdatePresentationRequest(['requests' => $requests]);
    $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);

    // Retrieve created slides
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

        $titleObjectId = $pageElements[0]->getObjectId() ?? null;
        $bodyObjectId = $pageElements[1]->getObjectId() ?? null;

        if ($titleObjectId) {
            $requests[] = new SlidesRequest([
                'insertText' => [
                    'objectId' => $titleObjectId,
                    'text' => $slideContent['title'],
                    'insertionIndex' => 0
                ]
            ]);
        }

        if ($bodyObjectId) {
            $requests[] = new SlidesRequest([
                'insertText' => [
                    'objectId' => $bodyObjectId,
                    'text' => $slideContent['body'],
                    'insertionIndex' => 0
                ]
            ]);
        }
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
    private function generateContentWithOpenAI(): array
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
                    [
                        'role' => 'system', 
                        'content' => 'Generate presentation slides with titles and body text. ' .
                                    'Format each slide as: ### Title: [SLIDE_TITLE]\n\n**Body Text:** [CONTENT]' .
                                    'Separate slides with ---. Keep body text concise (3-4 bullet points).'
                    ],
                    [
                        'role' => 'user', 
                        'content' => 'Generate 3 slides about AI advancements with professional formatting.'
                    ]
                ],
                'temperature' => 0.7
            ]
        ]);
    
        $data = json_decode($response->getBody(), true);
        
        $generatedText = $data['choices'][0]['message']['content'] ?? '';
        $slides = [];
    
        foreach (explode("---", $generatedText) as $slideText) {
            if (empty(trim($slideText))) continue;
    
            preg_match('/### Title: (.*?)\n/', $slideText, $titleMatch);
            preg_match('/\*\*Body Text:\*\*\s*(.*)/s', $slideText, $bodyMatch);
    
            $slides[] = [
                'title' => $titleMatch[1] ?? 'Untitled',
                'body' => trim($bodyMatch[1] ?? '')
            ];
        }
    
        return ['slides' => $slides];
    }

    private function formatSlidesData(array $openAiSlides): array
    {
        // Log the input for debugging
        Log::info('Formatting slides data:', ['input' => $openAiSlides]);
    
        $formattedSlides = [];
    
        foreach ($openAiSlides as $slide) {
            if (!isset($slide['title']) || !isset($slide['body'])) {
                continue;
            }
    
            // Clean up the title (remove "Title:" if present)
            $title = str_replace('Title:', '', $slide['title']);
            $title = trim($title);
    
            // Format the body text with proper bullet points
            $body = $this->formatBodyText($slide['body']);
    
            $formattedSlides[] = [
                'title' => $title,
                'body' => $body
            ];
        }
    
        return $formattedSlides;
    }
    
    private function formatBodyText(string $text): string
    {
        // Split into sentences and format as bullet points
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Trim each sentence and add bullet points
        $formatted = array_map(function($sentence) {
            return '• ' . trim($sentence);
        }, $sentences);
    
        return implode("\n", $formatted);
    }

    
    
    

    
}
