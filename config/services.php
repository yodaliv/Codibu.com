<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
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


    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'godaddy' => [
        'key'     => env("GODADDY_KEY"),
        'secret'  => env("GODADDY_SECRET"),
        'testing' => env("GODADDY_TESTING"),
    ],

    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . env('INSTAGRAM_CLIENT_REDIRECT'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . env('FACEBOOK_CLIENT_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . env('GOOGLE_CLIENT_REDIRECT'),
    ],

    'amazon' => [
        'client_id' => env('AMAZON_CLIENT_ID'),
        'client_secret' => env('AMAZON_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . env('AMAZON_CLIENT_REDIRECT'),
        'storeId' => env('AMAZON_STORE_ID'),
        'public_key_id' => env('AMAZON_PUBLIC_KEY_ID'),
        'private_key' => env('AMAZON_PRIVATE_KEY'),
        'region' => env('AMAZON_REGION'),
        'checkoutReviewReturnUrl' => env('APP_URL') . env('AMAZON_CHECKOUT_REVIEW_RETURN_URL'),
        'checkoutResultReturnUrl' => env('APP_URL') . env('AMAZON_CHECKOUT_RESULT_RETURN_URL'),
        'merchant_id' => env('AMAZON_MERCHANT_ID'),
        'sandbox' => env('AMAZON_SANDBOX'),
        'class' => App\Services\AmazonPayService::class,
    ],

    'paypal' => [
        'product_id' => env('PAYPAL_SANDBOX_PRODUCT_ID'),
        'return_url' => env('APP_URL') . env('PAYPAL_RETURN_URL'),
        'cancel_url' => env('APP_URL') . env('PAYPAL_CANCEL_URL'),
        'class' => App\Services\PaypalService::class,
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'product_id' => env('STRIPE_PRODUCT_ID'),
        'class' => App\Services\StripeService::class,
    ],
    'cpanel' => [
        'host' => env('CPANEL_HOST'),
        'port' => env('CPANEL_PORT'),
        'user' => env('CPANEL_USER'),
        'token' => env('CPANEL_TOKEN'),
    ]
];
