<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\JSON;
use VladViolentiy\VivaFramework\Tests\Models\PostItem;
use VladViolentiy\VivaFramework\Tests\Models\UserItem;

class JSONTest extends TestCase
{
    public function testMarshal():void{
        $info = '{"userId":1}';
        /** @var UserItem $response */
        $response = JSON::Unmarshal($info,UserItem::class);
        $this->assertEquals(1,$response->userId);
    }

    public function testRecursiveItem():void{
        $info = '{"header":"123","text":"123","owner":{"userId":1}}';
        /** @var PostItem $response */
        $response = JSON::Unmarshal($info,PostItem::class);
        $this->assertEquals(1,$response->owner->userId);
    }
}