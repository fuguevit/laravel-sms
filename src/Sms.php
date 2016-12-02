<?php

namespace Fuguevit\Sms;

use Fuguevit\Sms\Contracts\AdapterInterface;
use Fuguevit\Sms\Contracts\SmsInterface;

class Sms implements SmsInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Sms constructor.
     *
     * @param AdapterInterface $adapter
     * @param null             $config
     */
    public function __construct(AdapterInterface $adapter, $config = null)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getInterfaceUrl()
    {
        return $this->adapter->getInterfaceUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function send($phone, $message)
    {
        return $this->adapter->send($phone, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendVerifyCode($mobile, $code, $timeout = 15)
    {
        return $this->adapter->sendVerifyCode($mobile, $code, $timeout);
    }
}
