<?php

namespace Vendon\core;

class Response
{
    /**
     * @param int $code
     * @return void
     */
    //vvv Sets Response Status Code
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }
}