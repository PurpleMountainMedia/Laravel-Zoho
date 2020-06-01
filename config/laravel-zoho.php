<?php

return [
    'client_id' => env('ZOHO_CLIENT_ID', null),

    'client_secret' => env('ZOHO_CLIENT_SECRET', null),

    'redirect_uri' => env('ZOHO_CLIENT_REDIRECT_URI', 'https://engine.havebike.test/oauth/zoho'),

    'user_email' => env('ZOHO_USER_EMAIL', null)
];