<?php

namespace Fuguevit\Sms\Adapter;

use Illuminate\Support\Facades\Config;

class MiaodiyunSmsAdapter extends AbstractAdapter
{
    protected $rest_url;
    protected $auth_token;
    protected $account_sid;
    
    public function __construct()
    {
        $this->rest_url = Config::get('laravel-sms.settings.miaodi.rest_url');
        $this->auth_token = Config::get('laravel-sms.settings.miaodi.auth_token');
        $this->account_sid = Config::get('laravel-sms.settings.miaodi.account_sid');
    }

    public function getUrl()
    {

    }

    public function unifyResponseData($response)
    {
        // TODO: Implement unifyResponseData() method.
    }

    public function send($phone, $message)
    {
        // TODO: Implement send() method.
    }

    public function sendVerifyCode($phone, $code, $timeout)
    {
        // TODO: Implement sendVerifyCode() method.
    }

}