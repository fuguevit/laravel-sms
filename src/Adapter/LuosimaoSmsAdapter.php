<?php

namespace Fuguevit\Sms\Adapter;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

/**
 * Class LuosimaoSmsAdapter.
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
        $result = ['status' => 'success', 'data' => null, 'message' => null];
        $data = json_decode($response);

        // if data is null, return error unknown.
        if (is_null($data)) {
            $result['status'] = 'error';
            $result['error_code'] = Config::get('laravel-sms.error_code.unknown');
            $result['message'] = Config::get('laravel-sms.error_msg.'.$response['error_code']);

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        // if error equals 0, returns success
        if ($data->error == 0) {
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        $result['status'] = 'error';
        switch ($data->error) {
            // phone failed
            case -40:
                $result['error_code'] = Config::get('laravel-sms.error_code.phone_failed');
                break;
            // signature lacked
            case -32:
                $result['error_code'] = Config::get('laravel-sms.error_code.signature_lacked');
                break;
            // frequency problem
            case -42:
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
        // Form Request.
        $client = new Client();
        $data = $client->request('POST', $destination, [
            'auth'        => [
                'username'     => 'api',
                'password'     => 'key-'.$this->getAuthToken(),
            ],
            'form_params' => [
                'message'      => $message,
                'mobile'       => $phone,
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
