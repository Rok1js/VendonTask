<?php

namespace Vendon\Exceptions;

class UnprocessableException extends \Exception
{
    /**
     * @var int
     */
    protected $code = 422;

    /**
     * @var array|mixed
     */
    private array $options;

    /**
     * @param $options
     * @param $message
     * @param $code
     * @param Exception|null $previous
     */
    public function __construct(
        $options = [],
        $message = '',
        $code = 442,
        Exception $previous = null,
    ) {
        parent::__construct($message, $code, $previous);

        $this->options = $options;
    }

    /**
     * @return mixed|string[]
     */
    public function getOptions(): mixed
    {
        return $this->options;
    }

}