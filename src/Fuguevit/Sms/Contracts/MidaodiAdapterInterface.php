<?php

namespace Fuguevit\Sms\Contracts;

interface MiaodiAdapterInterface
{
    public function getUrl();

    public function sendSms($mobile, $message);

    public function sendVerifyCode($mobile, $code, $invalid=15);
}