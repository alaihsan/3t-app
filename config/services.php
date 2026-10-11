<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'quran_foundation' => [
        'client_id' => env('QURAN_FOUNDATION_CLIENT_ID'),
        'client_secret' => env('QURAN_FOUNDATION_CLIENT_SECRET'),
        'token_url' => env('QURAN_FOUNDATION_TOKEN_URL', 'https://oauth2.quran.foundation/oauth2/token'),
        'api_base_url' => env('QURAN_FOUNDATION_API_BASE_URL', 'https://apis.quran.foundation/content/api/v4'),
        // Leave null to auto-discover and cache the Indonesian translation resource id.
        'translation_resource_id' => env('QURAN_FOUNDATION_TRANSLATION_ID'),
    ],

];
