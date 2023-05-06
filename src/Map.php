<?php

namespace VladViolentiy\VivaFramework;

abstract class Map
{
    /**
     * @param list<array<string,int|string|float>> $data
     * @param non-empty-string $value
     * @return array<int|string,array<string,int|string|float>>
     */
    public static function valueAsKey(array $data, string $value):array{
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
    public static function singleValue(array $data, string $value):array{
        return array_map(function($item) use ($value){
            return $item[$value];
        },$data);
    }

    /**
     * @param list<array<string,string|int|float>> $data
     * @param non-empty-string $param
     * @param non-empty-string $value
     * @return array<int|string,string|int|float>
     */
    public static function paramValue(array $data, string $param, string $value):array{
        $i = [];
        foreach ($data as $item) {
            $i[$item[$param]] = $item[$value];
        }
        return $i;
    }
}