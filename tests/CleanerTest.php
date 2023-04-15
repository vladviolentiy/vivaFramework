<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\Cleaner;

class CleanerTest extends TestCase{
    public function testPhoneCleaner():void{
        $this->assertEquals("79999999999",Cleaner::phoneNumber("+79999999999"));
        $this->assertEquals("79999999999",Cleaner::phoneNumber("    +79999999999 "));
    }
}