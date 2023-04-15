<?php

namespace VladViolentiy\VivaFramework;

abstract class SuccessResponse
{
    /**
     * @param array<mixed> $data
     * @return array{success:bool,data:mixed}
     */
    public static function data(array $data):array{
        return [
            "success"=>true,
            "data"=>$data
        ];
    }

    /**
     * @param string $text
     * @return array{success:bool,text:string}
     */
    public static function text(string $text):array{
        return [
            "success"=>true,
            "text"=>$text
        ];
    }
}