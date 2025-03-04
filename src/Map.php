<?php

namespace VladViolentiy\VivaFramework;

abstract class Map
{
    /**
     * @param list<array<string,int|string|float>> $data
     * @param non-empty-string $value
     * @return array<int|float|string,array<string,int|string|float>>
     */
    public static function valueAsKey(array $data, string $value): array
    {
        $i = [];
        foreach ($data as $item) {
            $element = $item[$value];
            unset($item[$value]);
            $i[$element] = $item;
        }

        return $i;
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
        return array_map(function ($item) use ($param) {
            $item[$param] = (bool) $item[$param];

            return $item;
        }, $data);
    }
}
