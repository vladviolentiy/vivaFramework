<?php

namespace VladViolentiy\VivaFramework\Tests;

use PHPUnit\Framework\TestCase;
use VladViolentiy\VivaFramework\Exceptions\ValidationException;
use VladViolentiy\VivaFramework\Validation;

class ValidationTest extends TestCase
{
    public function testEmpty(): void
    {
        $this->expectException(ValidationException::class);
        Validation::nonEmpty("");
    }
    public function testEmail(): void
    {
        Validation::email("a@a.com");
        $this->assertTrue(true);
    }

    public function testHash(): void
    {
        Validation::hash("9a0a82f0c0cf31470d7affede3406cc9aa8410671520b727044eda15b4c25532a9b5cd8aaf9cec4919d76255b6bfb00f");
        $this->assertTrue(true);
    }

    public function testUUID(): void
    {
        Validation::uuid("2f7bdad5-f4e7-457b-acab-b7eda3913567");
        $this->assertTrue(true);
    }

    public function testDate(): void
    {
        Validation::date("2000-01-01");
        $this->assertTrue(true);
    }

    public function testIncorrectDate(): void
    {
        $this->expectException(ValidationException::class);
        Validation::date("2000-01-32");
    }

    public function testIncorrectDate2(): void
    {
        $this->expectException(ValidationException::class);
        Validation::date("2000-13-01");
    }

    public function testTime(): void
    {
        Validation::time("04:00:00");
        $this->assertTrue(true);
    }

    public function testIncorrectSeconds(): void
    {
        $this->expectException(ValidationException::class);
        Validation::time("04:00:60");
    }

    public function testIncorrectHours(): void
    {
        $this->expectException(ValidationException::class);
        Validation::time("-01:00:00");
    }
    public function testIncorrectHours2(): void
    {
        $this->expectException(ValidationException::class);
        Validation::time("4:00:00");
    }
    public function testIncorrectHours3(): void
    {
        $this->expectException(ValidationException::class);
        Validation::time("24:00:00");
    }
}
