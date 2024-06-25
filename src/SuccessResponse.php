<?php

namespace VladViolentiy\VivaFramework;

abstract class SuccessResponse
{
    /**
     * @param array<mixed> $data
     * @return array{success:true,data:array<mixed>}
     */
    public static function data(array $data): array
    {
        return [
            "success" => true,
            "data" => $data
        ];
    }

    /**
     * @param string $text
     * @return array{success:true,text:string}
     */
    public static function text(string $text): array
    {
        return [
            "success" => true,
            "text" => $text
        ];
    }

    /**
     * @return array{success:true}
     */
    public static function null(): array
    {
        return [
            "success" => true,
        ];
    }
}
