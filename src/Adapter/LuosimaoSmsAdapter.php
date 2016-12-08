<?php

namespace Fuguevit\Sms\Adapter;

class LuosimaoSmsAdapter extends AbstractAdapter
{
    protected $rest_url;
    protected $auth_token;
    protected $account_sid;

    /**
     * LuosimaoSmsAdapter constructor.
     */
    public function __construct()
    {
        $this->setAccountSid();
        $this->setAuthToken();
        $this->setInterfaceUrl();
    }

    /**
     * Set Account Sid.
     */
    public function setAccountSid()
    {
        $this->account_sid = Config::get('laravel-sms.settings.luosimao.account_sid');
    }

    /**
     * Get Account Sid.
     *
     * @return mixed
     */
    public function getAccountSid()
    {
        return $this->account_sid;
    }

    /**
     * Set Authentication Token.
     */
    public function setAuthToken()
    {
        $this->auth_token = Config::get('laravel-sms.settings.luosimao.auth_token');
    }

    /**
     * Get AuthToken.
     *
     * @return mixed
     */
    public function getAuthToken()
    {
        return $this->auth_token;
    }

    /**
     * Set Interface Url.
     */
    public function setInterfaceUrl()
    {
        $this->rest_url = Config::get('laravel-sms.settings.luosimao.rest_url');
    }

    /**
     * {@inheritdoc}
     */
    public function getInterfaceUrl()
    {
        return $this->rest_url;
    }

    /**
     * {@inheritdoc}
     */
    public function unifyResponseData($response)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function send($phone, $message)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function sendVerifyCode($phone, $code, $timeout)
    {
    }
    
}
