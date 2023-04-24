<?php

namespace VladViolentiy\VivaFramework;

abstract class Map
{
    public static function valueAsKey(array $data, string $value):array{
        $i = [];
        foreach ($data as $item) {
            $element = $item[$value];
            unset($item[$value]);
            $i[$element] = $item;
        }
        return $i;
    }

    public static function singleValue(array $data, string $value):array{
        return array_map(function($item) use ($value){
            return $item[$value];
        },$data);
    }

    public static function paramValue(array $data,string $param,string $value):array{
        $i = [];
        foreach ($data as $item) {
            $i[$item[$param]] = $item[$value];
        }
        return $i;
    }
}