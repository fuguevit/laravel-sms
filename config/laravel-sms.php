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
    | Default Application Name
    |--------------------------------------------------------------------------
    |
    | Application name will be added before your message body.
    |
    */
    'application' => 'laravel-sms',

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
            'verify_uri'  => '/20150822/industrySMS/sendSMS',
            'template'    => [
                'verify' => '您的手机验证码为{1}，有效时间为{2}分钟，请妥善保管并及时使用该验证码。如非本人操作，请忽略此信息。',
            ],
        ],
    ],




    /*
    |--------------------------------------------------------------------------
    | SMS Unify Error Code
    |--------------------------------------------------------------------------
    |
    |  When you implement a new adapter, you should convert error code to
    |  the unify error code.
    |
    */
    'error_code' => [

        'msg_verify_failed'    => -10,
        'interface_forbidden'  => -11,
        'balance_lacked'       => -20,
        'content_null'         => -30,
        'censor_failed'        => -31,
        'signature_lacked'     => -32,
        'message_too_long'     => -33,
        'signature_failed'     => -34,
        'phone_failed'         => -40,
        'phone_in_black_list'  => -41,
        'verify_frequency'     => -42,
        'ip_not_in_white_list' => -50,
        'unknown'              => -90,
    ],

    'error_msg' => [

        '-10' => '验证信息失败',
        '-11' => '用户接口被禁用',
        '-20' => '短信余额不足',
        '-30' => '短信内容为空',
        '-31' => '短信内容存在敏感词',
        '-32' => '短信内容缺少签名信息',
        '-33' => '短信过长',
        '-34' => '签名不可用',
        '-40' => '错误的手机号',
        '-41' => '号码在黑名单中',
        '-42' => '验证码类短信发送频率过快',
        '-50' => '请求发送IP不在白名单内',
        '-90' => '未知错误',
    ],

];
