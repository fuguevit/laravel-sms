<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS System
    |--------------------------------------------------------------------------
    |
    | Supported: "miaodi"
    |
    */
    'default' => 'miaodi',

    /*
    |--------------------------------------------------------------------------
    | Default Signature
    |--------------------------------------------------------------------------
    |
    | Signature will be added before your message body.
    |
    */
    'signature' => 'laravel-sms',

    /*
    |--------------------------------------------------------------------------
    | SMS System Settings
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many sms system "settings" as you wish.
    |
    */
    'settings' => [

        'miaodi' => [

            'account_sid' => '',
            'auth_token'  => '',
            'rest_url'    => 'https://api.miaodiyun.com',
            'verify_url'  => 'https://api.miaodiyun.com/20150822/industrySMS/sendSMS',

            'template' => [
                'verify' => '您的手机验证码为{1}，有效时间为{2}分钟，请妥善保管并及时使用该验证码。如非本人操作，请忽略此信息。',
            ],
        ],

        
    ],

];