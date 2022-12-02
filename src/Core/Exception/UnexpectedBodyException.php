<?php

namespace PospalHelper\Core\Exception;

use Throwable;

class UnexpectedBodyException extends PospalUnexpectedException
{
    protected string $body = '';

    public function __construct($message = "", $code = 0, Throwable $previous = null, $body = '')
    {
        parent::__construct($message, $code, $previous);

        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}