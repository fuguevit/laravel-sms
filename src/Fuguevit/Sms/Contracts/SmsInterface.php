<?php

namespace Fuguevit\Sms\Contracts;

interface SmsInterface
{
    public function getUrl();
    
    public function send($phone, $message);
    
    public function sendVerifyCode($mobile, $code, $timeout=15);
}