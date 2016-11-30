<?php

namespace Fuguevit\Sms\Adapter;

use Fuguevit\Sms\Contracts\AdapterInterface;

abstract class AbstractAdapter implements AdapterInterface
{
    public abstract function unifyResponseData($response);

    public function reply($data)
    {
        return $data;
    }
}