<?php

namespace Fuguevit\Sms\Contracts;

interface SmsInterface
{
    public function getInterfaceUrl();
    
    public function send($phone, $message);
    
    public function sendVerifyCode($mobile, $code, $timeout=15);
}