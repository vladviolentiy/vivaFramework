<?php

namespace VladViolentiy\VivaFramework\Exceptions;

use Throwable;

class ValidationException extends \Exception
{
    public function __construct(string $message = 'Validation exception', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
