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
        // @phpstan-ignore-next-line
        Validation::nonEmpty('');
    }
    public function testEmail(): void
    {
        $this->expectNotToPerformAssertions();
        Validation::email('a@a.com');
    }

    public function testHash(): void
    {
        $this->expectNotToPerformAssertions();
        // @phpstan-ignore-next-line
        Validation::hash('9a0a82f0c0cf31470d7affede3406cc9aa8410671520b727044eda15b4c25532a9b5cd8aaf9cec4919d76255b6bfb00f');
    }

    public function testUUID(): void
    {
        $this->expectNotToPerformAssertions();
        Validation::uuid('2f7bdad5-f4e7-457b-acab-b7eda3913567');
    }

    public function testDate(): void
    {
        $this->expectNotToPerformAssertions();
        // @phpstan-ignore-next-line
        Validation::date('2000-01-01');
    }

    public function testIncorrectDate(): void
    {
        $this->expectException(ValidationException::class);
        // @phpstan-ignore-next-line
        Validation::date('2000-01-32');
    }

    public function testIncorrectDate2(): void
    {
        $this->expectException(ValidationException::class);
        // @phpstan-ignore-next-line
        Validation::date('2000-13-01');
    }


    public function testIncorrectHours2(): void
    {
        $this->expectException(ValidationException::class);
        // @phpstan-ignore-next-line
        Validation::time('4:00:00');
    }

    public function testIncorrectHours3(): void
    {
        $this->expectException(ValidationException::class);
        // @phpstan-ignore-next-line
        Validation::time('24:00:00');
    }
}
