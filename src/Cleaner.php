<?php

namespace VladViolentiy\VivaFramework;

abstract class Cleaner
{
    /**
     * @param non-empty-string $phone
     * @return numeric-string
     */
    public static function phoneNumber(string $phone):string{
        /** @var string $phone */
        $phone = filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
        /** @var numeric-string $i */
        $i = str_replace(['+',"-"],"",$phone);
        return $i;
    }
}