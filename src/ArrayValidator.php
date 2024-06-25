<?php

namespace VladViolentiy\VivaFramework;

use VladViolentiy\VivaFramework\Exceptions\ValidationException;

class ArrayValidator
{
    /**
     * @param array<mixed> $input
     * @return void
     * @throws ValidationException
     */
    public static function intList(array $input): void
    {
        foreach ($input as $item) {
            if(!is_int($item)) {
                throw new ValidationException();
            }
        }
    }

    /**
     * @phpstan-assert string[] $input
     * @param array<mixed> $input
     * @return void
     * @throws ValidationException
     */
    public static function stringList(array $input): void
    {
        foreach ($input as $item) {
            if(!is_string($item)) {
                throw new ValidationException();
            }
        }
    }
}
