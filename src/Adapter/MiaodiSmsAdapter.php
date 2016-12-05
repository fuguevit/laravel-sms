<?php

namespace Fuguevit\Sms\Adapter;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class MiaodiSmsAdapter extends AbstractAdapter
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
        $result = ['status' => 'success', 'data' => null, 'message' => null];
        $data = json_decode($response);

        // if data is null, return error unknown.
        if (is_null($data)) {
            $result['status'] = 'error';
            $result['error_code'] = Config::get('laravel-sms.error_code.unknown');
            $result['message'] = Config::get('laravel-sms.error_msg.'.$response['error_code']);

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        // if respCode equals '00000', returns success
        if ($data->respCode == '00000') {
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        $result['status'] = 'error';
        switch ($data->respCode) {
            // phone failed
            case '00025':
                $result['error_code'] = Config::get('laravel-sms.error_code.phone_failed');
                break;
            // signature lacked
            case '00138':
                $result['error_code'] = Config::get('laravel-sms.error_code.signature_lacked');
                break;
            // frequency problem
            case '00412':case '00413':case '00519':
                $result['error_code'] = Config::get('laravel-sms.error_code.verify_frequency');
                break;
            // default
            default:
                $result['error_code'] = Config::get('laravel-sms.error_code.unknown');
        }

        $result['message'] = Config::get('laravel-sms.error_msg.'.$result['error_code']);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritdoc}
     */
    public function send($phone, $message)
    {
        $destination = $this->getDestination();
        $current_ts = Carbon::now()->format('YmdHis');
        // Calculate Signature.
        $signature = md5($this->getAccountSid().$this->getAuthToken().$current_ts);
        // Form Request.
        $client = new Client();
        $data = $client->request('POST', $destination, [
            'form_params' => [
                'accountSid'   => $this->getAccountSid(),
                'smsContent'   => $message,
                'to'           => $phone,
                'timestamp'    => $current_ts,
                'sig'          => $signature,
                'respDataType' => 'JSON',
            ],
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
        $this->setDestination($this->getInterfaceUrl().Config::get('laravel-sms.settings.miaodi.verify_uri'));

        $application = Config::get('laravel-sms.application');
        $body = Config::get('laravel-sms.settings.miaodi.template.verify');
        $raw_content = $application.$body;

        // Replace template to user specific message.
        $message = str_replace(['{1}', '{2}'], [$code, $timeout], $raw_content);

        // Send Message.
        $response = $this->send($phone, $message);

        return $response;
    }
}
