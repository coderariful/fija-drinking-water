<?php

return [
    'endpoint' => env('SMS_ENDPOINT', "https://smsapi.shiramsystem.com/user_api/"),
    'email' => env('SMS_API_EMAIL'),
    'secret' => env('SMS_API_SECRET'),
    'sandbox' => env('SMS_API_SANDBOX', false),
    'test_number' => env('SMS_API_TEST_NUMBER', "8801755900055"),
    'mask' => env('SMS_API_MASK', 'FIJA WATER'),
    'bkash_number' => env("BKASH_NUMBER", "01715552398"),
    'enabled' => env('SMS_API_ENABLED', true),
];
