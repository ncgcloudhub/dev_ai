<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL1') . '/google/callback',
        'scopes' => [
        'https://www.googleapis.com/auth/presentations', // Required for Google Slides
        'https://www.googleapis.com/auth/drive', // Optional, if you need Drive access
    ],
    'access_type' => 'offline', // Ensure offline access
    'prompt' => 'consent', // Force consent prompt

    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('APP_URL1') . '/github/callback',
    ],

    'heyzine' => [
        'client_id' => env('HEYZINE_CLIENT_ID'),
        'api_key' => env('HEYZINE_API_KEY'),
    ],


    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stable_diffusion' => [
        'api_key' => env('STABLE_DIFFUSION_API_KEY'),
        'api_url' => env('STABLE_DIFFUSION_API_URL'),
    ],

    'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    
    
],


];
