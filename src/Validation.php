<?php

namespace VladViolentiy\VivaFramework;

use VladViolentiy\VivaFramework\Exceptions\ValidationException;

abstract class Validation
{
    /** @phpstan-assert non-empty-string $hash */
    public static function empty(string $value,string $errorText = "String is empty"):void{
        if($value==="") throw new ValidationException($errorText);
    }

    public static function email(string $value,string $errorText = "Email incorrect"):void{
        if(!filter_var($value,FILTER_VALIDATE_EMAIL)) throw new ValidationException($errorText);
    }

    /** @phpstan-assert non-empty-string $hash */
    public static function hash(string $value,int $length=96,string $errorText = "Incorrect hash value"):void{
        if(!preg_match('/^[0-9a-f]{'.$length.'}$/',$value)) throw new ValidationException($errorText);
    }

    /** @phpstan-assert positive-int $item */
    public static function id(int $item):void{
        if($item<=0) throw new ValidationException();
    }

    public static function uuid(string $uuid,string $errorText = "Incorrect UUID value"):void{
        if(!preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[1-8][0-9A-Fa-f]{3}-[ABab89][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$/',$uuid)) throw new ValidationException($errorText);
    }
}