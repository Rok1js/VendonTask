<?php

namespace Vendon\Exceptions;

class ForbiddenException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'You don\'t have permission to access this page';

    /**
     * @var int
     */
    protected $code = 403;
}