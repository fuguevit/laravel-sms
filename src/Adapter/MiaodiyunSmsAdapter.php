<?php

namespace Fuguevit\Sms\Adapter;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class MiaodiyunSmsAdapter extends AbstractAdapter
{
    protected $rest_url;
    protected $auth_token;
    protected $account_sid;

    /**
     * MiaodiyunSmsAdapter constructor.
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
        $this->account_sid = Config::get('laravel-sms.settings.miaodi.account_sid');
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
        $this->auth_token = Config::get('laravel-sms.settings.miaodi.auth_token');
    }

    /**
     * Get AuthToken
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
        $this->rest_url = Config::get('laravel-sms.settings.miaodi.rest_url');
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
        $client = new Client();

        $destination = $this->getDestination();
        $current_ts = Carbon::now()->format('YmdHis');
        // Calculate Signature.
        $signature = md5($this->account_sid.$this->auth_token.$current_ts);
        // Form Request.
        $request = $client->post($destination)
            ->addPostFields(
                [
                    'accountSid' => $this->getAccountSid(),
                    'smsContent' => $message,
                    'to' => $phone,
                    'timestamp' => $current_ts,
                    'sig' => $signature,
                    'respDataType' => 'JSON'
                ],
                ('Content-Type: application/x-www-form-urlencoded')
            );

        // Send Request.
        $data = $request->send();

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
        $this->setDestination($this->getInterfaceUrl() . Config::get('laravel-sms.settings.miaodi.verify_uri'));

        $application = Config::get('laravel-sms.application');
        $body = Config::get('laravel-sms.settings.miaodi.template.verify');
        $raw_content = $application . $body;

        // Replace template to user specific message.
        $message = str_replace(['{1}', '{2}'], [$code, $timeout], $raw_content);

        // Send Message.
        $response = $this->send($phone, $message);

        return $response;
    }

}