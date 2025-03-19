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
        // Set the access token from the authenticated user
        $user = $request->user();
    
        // Decode the token from JSON
        $token = json_decode($user->google_token, true);
    
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($token)) {
            throw new \Exception('Invalid token format in database.');
        }
    
        // Set the token for the Google API client
        $this->client->setAccessToken($token);
    
        // Refresh the token if it's expired
        if ($this->client->isAccessTokenExpired()) {
            if (empty($token['refresh_token'])) {
                throw new \Exception('No refresh token available.');
            }
    
            // Fetch a new access token using the refresh token
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
            $this->client->setAccessToken($newToken);
    
            // Update the user's tokens in the database
            $user->google_token = json_encode($newToken);
            $user->save();
        }
    
        // Create a new Google Slides service instance
        $slidesService = new GoogleSlides($this->client);
    
        // Create a new presentation
        $presentation = new Presentation([
            'title' => 'My New Presentation'
        ]);
        $presentation = $slidesService->presentations->create($presentation);
    
        // Add a slide with a title and body layout
        $requests = [
            new SlidesRequest([
                'createSlide' => [
                    'objectId' => 'slide1', // Unique ID for the slide
                    'insertionIndex' => 1, // Position of the slide in the presentation
                    'slideLayoutReference' => [
                        'predefinedLayout' => 'TITLE_AND_BODY' // Use a layout with a text box
                    ]
                ]
            ])
        ];
    
        $batchUpdateRequest = new BatchUpdatePresentationRequest([
            'requests' => $requests
        ]);
    
        // Execute the batch update to create the slide
        $response = $slidesService->presentations->batchUpdate($presentation->presentationId, $batchUpdateRequest);
    
        // Retrieve the slide's element IDs
        $slideId = $response->getReplies()[0]->getCreateSlide()->getObjectId();
        $slide = $slidesService->presentations->get($presentation->presentationId, ['fields' => 'slides'])->getSlides()[0];
    
        // Log the slide elements for debugging
        Log::info('Slide Elements: ', $slide->getPageElements());
    
        // Find the title and body placeholders
        $titleObjectId = null;
        $bodyObjectId = null;
    
        foreach ($slide->getPageElements() as $element) {
            if ($element->getShape() && $element->getShape()->getPlaceholder()) {
                $placeholderType = $element->getShape()->getPlaceholder()->getType();
                if ($placeholderType === 'TITLE') {
                    $titleObjectId = $element->getObjectId();
                } elseif ($placeholderType === 'BODY') {
                    $bodyObjectId = $element->getObjectId();
                }
            }
        }
    
        // If placeholders are missing, create text boxes manually
        if (!$titleObjectId || !$bodyObjectId) {
            $requests = [];
    
            if (!$titleObjectId) {
                $titleObjectId = 'title-box';
                $requests[] = new SlidesRequest([
                    'createShape' => [
                        'objectId' => $titleObjectId,
                        'shapeType' => 'TEXT_BOX',
                        'elementProperties' => [
                            'pageObjectId' => $slideId,
                            'size' => [
                                'width' => ['magnitude' => 500, 'unit' => 'PT'],
                                'height' => ['magnitude' => 50, 'unit' => 'PT']
                            ],
                            'transform' => [
                                'scaleX' => 1,
                                'scaleY' => 1,
                                'translateX' => 50,
                                'translateY' => 50,
                                'unit' => 'PT'
                            ]
                        ]
                    ]
                ]);
            }
    
            if (!$bodyObjectId) {
                $bodyObjectId = 'body-box';
                $requests[] = new SlidesRequest([
                    'createShape' => [
                        'objectId' => $bodyObjectId,
                        'shapeType' => 'TEXT_BOX',
                        'elementProperties' => [
                            'pageObjectId' => $slideId,
                            'size' => [
                                'width' => ['magnitude' => 500, 'unit' => 'PT'],
                                'height' => ['magnitude' => 200, 'unit' => 'PT']
                            ],
                            'transform' => [
                                'scaleX' => 1,
                                'scaleY' => 1,
                                'translateX' => 50,
                                'translateY' => 150,
                                'unit' => 'PT'
                            ]
                        ]
                    ]
                ]);
            }
    
            // Execute the batch update to create text boxes
            $batchUpdateRequest = new BatchUpdatePresentationRequest([
                'requests' => $requests
            ]);
            $slidesService->presentations->batchUpdate($presentation->presentationId, $batchUpdateRequest);
        }
    
        // Insert text into the title and body placeholders
        $requests = [
            new SlidesRequest([
                'insertText' => [
                    'objectId' => $titleObjectId, // Use the correct objectId for the title placeholder
                    'text' => 'Hello, World!', // Text to insert
                    'insertionIndex' => 0 // Position of the text in the text box
                ]
            ]),
            new SlidesRequest([
                'insertText' => [
                    'objectId' => $bodyObjectId, // Use the correct objectId for the body placeholder
                    'text' => 'This is the body text.', // Text to insert
                    'insertionIndex' => 0 // Position of the text in the text box
                ]
            ])
        ];
    
        $batchUpdateRequest = new BatchUpdatePresentationRequest([
            'requests' => $requests
        ]);
    
        // Execute the batch update to insert text
        $slidesService->presentations->batchUpdate($presentation->presentationId, $batchUpdateRequest);
    
        return response()->json([
            'message' => 'Slide created successfully!',
            'presentationId' => $presentation->presentationId
        ]);
    }
}
