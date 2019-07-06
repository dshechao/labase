<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys while generating secure access tokens for
    | your application. By default, the keys are stored as local files but
    | can be set via environment variables when that is more convenient.
    |
    */
    'private_key' => env('PASSPORT_PRIVATE_KEY'),
    'public_key'  => env('PASSPORT_PUBLIC_KEY'),
    //前台密码登录客户端信息
    'users'       => [
        'client_id'     => '3',
        'client_secret' => '1RIcmZ59rVs6U6fVHt4VzF8CXPxaneYlVOf0FFV9',
    ],
    //后端密码登录客户端信息
    'admins'       => [
        'client_id'     => '4',
        'client_secret' => '0N2HwaQuQnHyjWFOhiDMMOXzNR9YXLOlsQrOwoEr',
    ],
];
