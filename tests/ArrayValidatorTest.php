<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\ArrayValidator;

class ArrayValidatorTest extends TestCase
{
    public function testIsIntList(): void
    {
        $info = '[1,2,3,4]';
        $decoded = json_decode($info, true);
        ArrayValidator::intList($decoded);
        self::assertTrue(true);
    }
    public function testIsStringList(): void
    {
        $info = '["333","123"]';
        $decoded = json_decode($info, true);
        ArrayValidator::stringList($decoded);
        self::assertTrue(true);
    }
}
