<?php

namespace VladViolentiy\VivaFramework\Exceptions;

use Throwable;

class NotfoundException extends \Exception
{
    public function __construct(string $message = "Resource not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}