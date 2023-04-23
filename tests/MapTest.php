<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\Map;

class MapTest extends TestCase
{
    public function testStringAsKey():void{
        $info = [
            [
                "id"=>1,
                "text"=>"test1"
            ],
            [
                "id"=>2,
                "text"=>"test2"
            ]
        ];
        $this->assertEquals([
            1=>[
                "text"=>"test1",
            ],
            2=>[
                "text"=>"test2"
            ]
        ],Map::valueAsKey($info,"id"));
    }
}