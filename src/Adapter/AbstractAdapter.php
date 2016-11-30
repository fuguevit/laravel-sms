<?php

namespace Fuguevit\Sms\Adapter;

use Fuguevit\Sms\Contracts\AdapterInterface;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $destination;

    public abstract function unifyResponseData($response);

    public function reply($data)
    {
        return $data;
    }

    public function setDestination($url)
    {
        $this->destination = $url;
    }

    public function getDestination()
    {
        return $this->destination;
    }
}