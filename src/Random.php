<?php

namespace VladViolentiy\VivaFramework;

abstract class Random
{
    /**
     * @param positive-int $count
     * @return non-empty-string
     */
    public static function get(int $count = 32): string
    {
        /** @phpstan-var non-empty-string $i */
        $i = openssl_random_pseudo_bytes($count);

        return $i;
    }

    /**
     * @param non-empty-string $value
     * @param non-empty-string $algo
     * @return non-empty-string
     */
    public static function hash(string $value, string $algo = 'sha384'): string
    {
        return hash($algo, $value);
    }
}
