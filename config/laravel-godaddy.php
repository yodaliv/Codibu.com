<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Required configuration
    |--------------------------------------------------------------------------
    |
    | Providing your GoDaddy API key and secret is a mandatory config step.
    | These can both be obtained from http://developer.godaddy.com/keys/
    |
    */

    'key'     => env("GODADDY_KEY"),
    'secret'  => env("GODADDY_SECRET"),
    'testing' => env("GODADDY_TESTING"),
    'debug'   => true,

    /*
    |--------------------------------------------------------------------------
    | Optional configuration
    |--------------------------------------------------------------------------
    |
    | Settings from here down are optional and required only if
    | you intend to use the GoDaddy::purchase() method call.
    |
    */

    'company-details' => [
        'name'         => 'Jino Chae',
        'surname'      => 'Chae',
        'email'        => '10ten92@gmail.com',
        'phone'        => '+551.2651909',
        'organization' => 'codibu',
        'street'       => '266 epps ave',
        'city'         => 'Englewood',
        'country'      => 'US',
        'postal-code'  => '07631',
        'state'        => 'NJ'
    ]
];
