<?php

namespace VladViolentiy\VivaFramework\Exceptions;

use Throwable;

class AccessRulesException extends \Exception
{
    public function __construct(string $message = "Access rules exception", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}