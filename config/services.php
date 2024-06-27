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

    'line' => [
        // Messaging Apiチャンネル
        'messaging_api_channel_id' => env('LINE_MESSAGING_API_CHANNEL_ID'),
        'messaging_api_channel_token' => env('LINE_MESSAGING_API_ACCESS_TOKEN'),
        'messaging_api_channel_secret' => env('LINE_MESSAGING_API_CHANNEL_SECRET'),
        // Line Login チャンネル
        'client_id' => env('LINE_LOGIN_CHANNEL_ID'),
        'client_secret' => env('LINE_LOGIN_CHANNEL_SECRET'),

        'redirect' => env('LINE_LOGIN_CALLBACK_URL'),

        'add_friend_url' => env('LINE_ADD_FRIEND_URL'),
    ],

    'stripe' => [
        'key' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

];
