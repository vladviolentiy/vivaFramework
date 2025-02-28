<?php

namespace VladViolentiy\VivaFramework;

abstract class Cleaner
{
    /**
     * @param string $phone
     * @return numeric-string
     */
    public static function phoneNumber(string $phone): string
    {
        /** @var string $phone */
        $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        /** @var numeric-string $result */
        $result = str_replace(['+', '-'], '', $phone);

        return $result;
    }
}
