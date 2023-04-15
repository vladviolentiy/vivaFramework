<?php

namespace VladViolentiy\VivaFramework;

use VladViolentiy\VivaFramework\Exceptions\ValidationException;

abstract class Validation
{
    public static function empty(string $value,string $errorText = "String is empty"):void{
        if($value==="") throw new ValidationException($errorText);
    }

    public static function email(string $value,string $errorText = "Email incorrect"):void{
        if(!filter_var($value,FILTER_VALIDATE_EMAIL)) throw new ValidationException($errorText);
    }
}