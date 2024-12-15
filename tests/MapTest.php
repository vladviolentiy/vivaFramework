<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\Map;

class MapTest extends TestCase
{
    public function testStringAsKey(): void
    {
        $info = [
            [
                'id' => 1,
                'text' => 'test1',
            ],
            [
                'id' => 2,
                'text' => 'test2',
            ],
        ];
        $this->assertEquals([
            1 => [
                'text' => 'test1',
            ],
            2 => [
                'text' => 'test2',
            ],
        ], Map::valueAsKey($info, 'id'));
    }

    public function testParamValue(): void
    {
        $info = [
            [
                'id' => 1,
                'text' => 'test1',
            ],
            [
                'id' => 2,
                'text' => 'test2',
            ],
        ];
        $this->assertEquals([
            1 => 'test1',
            2 => 'test2',
        ], Map::paramValue($info, 'id', 'text'));
    }

    public function testSingleValue(): void
    {
        $info = [
            [
                'id' => 1,
            ],
            [
                'id' => 2,
            ],
        ];
        $this->assertEquals([1, 2], Map::singleValue($info, 'id'));
    }

    public function testToBoolValue(): void
    {
        $info = [
            [
                'id' => 1,
            ],
            [
                'id' => 0,
            ],
        ];
        $this->assertEquals([
            [
                'id' => true,
            ],
            [
                'id' => false,
            ],
        ], Map::toBoolValue($info, 'id'));
    }
}
