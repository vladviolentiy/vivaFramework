<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\Validation;

class ValidationTest extends TestCase
{
    public function emptyTest():void{
        Validation::nonEmpty("123");
        $this->assertTrue(true);
    }
    public function testEmail():void{
        Validation::email("a@a.com");
        $this->assertTrue(true);
    }

    public function testHash():void{
        Validation::hash("9a0a82f0c0cf31470d7affede3406cc9aa8410671520b727044eda15b4c25532a9b5cd8aaf9cec4919d76255b6bfb00f");
        $this->assertTrue(true);
    }
}