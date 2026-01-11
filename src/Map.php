<?php

namespace VladViolentiy\VivaFramework;

use VladViolentiy\VivaFramework\Exceptions\ValidationException;

/**
 * @api
 */
abstract class Map
{
    /**
     * Transforms an array of associative arrays by using a specified key's value as the new key.
     *
     * @param list<array<non-empty-string, int|string|float|array<mixed>>> $data Input array of associative arrays
     * @param non-empty-string $key The key whose value will become the new array key
     * @return array<non-empty-string, array<string, int|string|float|array<mixed>>> Transformed array
     */
    public static function valueAsKey(array $data, string $key): array
    {
        $result = [];

        foreach ($data as $item) {
            // Extract the value that will become the new key
            $newKey = $item[$key];

            if (is_array($newKey)) {
                throw new ValidationException('key cannot be an array');
            }

            // Convert numeric keys to strings to avoid potential issues
            if (is_int($newKey) || is_float($newKey)) {
                $newKey = (string) $newKey;
            }

            if (empty($newKey)) {
                throw new ValidationException('key cannot be an empty');
            }

            // Remove the original key-value pair
            unset($item[$key]);

            // Assign to the result array
            $result[$newKey] = $item;
        }

        return $result;
    }

    /**
     * @param list<array<string,string|int|float>> $data
     * @param non-empty-string $value
     * @return list<string|int|float>
     */
    public static function singleValue(array $data, string $value): array
    {
        return array_column($data, $value);
    }

    /**
     * @param list<array<string,string|int|float>> $data
     * @param non-empty-string $param
     * @param non-empty-string $value
     * @return array<int|float|string,string|int|float>
     */
    public static function paramValue(array $data, string $param, string $value): array
    {
        return array_column($data, $value, $param);
    }

    /**
     * @param list<array<string,string|int|float>> $data
     * @param non-empty-string $param
     * @return list<array<string,string|int|float|bool>>
     */
    public static function toBoolValue(array $data, string $param): array
    {
        return array_map(static function ($item) use ($param) {
            $item[$param] = (bool) $item[$param];

            return $item;
        }, $data);
    }
}
