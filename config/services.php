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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sms' => [
        'client_id'         => env('SMS_USERNAME'),
        'client_secret'     => env('SMS_PASSWORD'),
        'client_senderid'   => env('SMS_SENDERID'),
    ],

    'sendinblue' => [
        'client_id'     => env('SENDINBLUE_USERNAME'),
        'client_secret' => env('SENDINBLUE_PASSWORD'),
        'client_key'    => env('SENDINBLUE_KEY'),
        'client_host'    => env('SENDINBLUE_HOST'),
        'client_port'    => env('SENDINBLUE_PORT'),
    ],

];
