<?php

namespace VladViolentiy\VivaFramework\Exceptions;

use Throwable;

class MigrationException extends \Exception
{
    public function __construct(string $message = "Migrations exception", int $code = 1, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
