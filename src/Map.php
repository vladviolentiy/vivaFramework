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
}