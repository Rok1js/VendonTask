<?php

namespace Vendon\Exceptions;

class NotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Route not found';

    /**
     * @var int
     */
    protected $code = 404;
}