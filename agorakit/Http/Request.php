<?php

namespace Agorakit\Http;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    public function expectsJson()
    {
        if ($this->hasHeader('X-Up-Target')) {
            return false;
        }

        return parent::expectsJson();
    }
}
