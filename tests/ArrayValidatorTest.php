<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\ArrayValidator;

class ArrayValidatorTest extends TestCase
{
    public function testIsIntList(): void
    {
        $this->expectNotToPerformAssertions();
        $info = '[1,2,3,4]';
        $decoded = json_decode($info, true);
        ArrayValidator::intList($decoded);
    }

    public function testIsStringList(): void
    {
        $this->expectNotToPerformAssertions();
        $info = '["333","123"]';
        $decoded = json_decode($info, true);
        // @phpstan-ignore-next-line
        ArrayValidator::stringList($decoded);
    }
}
