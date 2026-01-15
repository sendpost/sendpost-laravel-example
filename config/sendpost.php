<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SendPost API Key
    |--------------------------------------------------------------------------
    |
    | Your SendPost Sub-Account API Key used for authenticating API requests.
    |
    */
    'api_key' => env('SENDPOST_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default From Address
    |--------------------------------------------------------------------------
    |
    | The default email address that will be used as the sender.
    |
    */
    'from_email' => env('SENDPOST_FROM_EMAIL', 'hello@playwithsendpost.io'),

    /*
    |--------------------------------------------------------------------------
    | Default From Name
    |--------------------------------------------------------------------------
    |
    | The default name that will be used as the sender name.
    |
    */
    'from_name' => env('SENDPOST_FROM_NAME', 'SendPost'),
];
