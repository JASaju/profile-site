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

    'ip_geolocation' => [
        'enabled' => env('IP_GEOLOCATION_ENABLED', false),
        'url' => env('IP_GEOLOCATION_URL', 'https://ipwho.is/{ip}'),
        'timeout' => env('IP_GEOLOCATION_TIMEOUT', 3),
        'cache_days' => env('IP_GEOLOCATION_CACHE_DAYS', 30),
        'requests_per_minute' => env('IP_GEOLOCATION_REQUESTS_PER_MINUTE', 30),
    ],

];
