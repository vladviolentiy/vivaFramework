<?php

namespace VladViolentiy\VivaFramework\Exceptions;

use Throwable;

class DatabaseException extends \Exception
{
    public function __construct(string $message = 'Database exception', int $code = 1, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
