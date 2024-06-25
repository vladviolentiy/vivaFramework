<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\SuccessResponse;

class SuccessResponseTest extends TestCase
{
    public function testDataResponse(): void
    {
        $info = [
            "test" => true
        ];

        $this->assertEquals([
            "success" => true,
            "data" => [
                "test" => true
            ]
        ], SuccessResponse::data($info));
    }
    public function testTextResponse(): void
    {
        $info = "test";

        $this->assertEquals([
            "success" => true,
            "text" => "test"
        ], SuccessResponse::text($info));
    }

    public function testNullResponse(): void
    {
        $this->assertEquals([
            "success" => true,
        ], SuccessResponse::null());
    }
}
