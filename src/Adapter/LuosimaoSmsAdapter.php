<?php

namespace Fuguevit\Sms\Adapter;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

/**
 * Class LuosimaoSmsAdapter
 * 
 * @package Fuguevit\Sms\Adapter
 */
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
        $destination = $this->getDestination();
        // Form Request.
        $client = new Client();
        $data = $client->request('POST', $destination, [
            'form_params' => [
                'message'      => $message,
                'mobile'       => $phone,
            ]
        ]);
        // Unify response data.
        $response = $this->unifyResponseData($data->getBody());

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function sendVerifyCode($phone, $code, $timeout)
    {
        // Set Destination Url.
        $this->setDestination($this->getInterfaceUrl().Config::get('laravel-sms.settings.luosimao.verify_uri'));

        $application = Config::get('laravel-sms.application');
        $body = Config::get('laravel-sms.settings.luosimao.template.verify');
        $raw_content = $application.$body;

        // Replace template to user specific message.
        $message = preg_replace('/xxx/', $code, $raw_content);
        $message = preg_replace('/xxx/', $code, $message);
        
        // Send Message.
        $response = $this->send($phone, $message);

        return $response;
    }
}
