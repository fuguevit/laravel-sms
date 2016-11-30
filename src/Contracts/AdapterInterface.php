<?php

namespace Fuguevit\Sms\Contracts;

interface AdapterInterface
{
    /**
     * @return mixed
     */
    public function getUrl();

    /**
     * @param $phone
     * @param $message
     * @return mixed
     */
    public function send($phone, $message);

    /**
     * @param $phone
     * @param $code
     * @param $timeout
     * @return mixed
     */
    public function sendVerifyCode($phone, $code, $timeout);
}