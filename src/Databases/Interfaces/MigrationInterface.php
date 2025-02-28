<?php

namespace VladViolentiy\VivaFramework\Databases\Interfaces;

use VladViolentiy\VivaFramework\Databases\Migrations\MigrationsClassInterface;

interface MigrationInterface
{
    public function __construct(MigrationsClassInterface $migrator);

    public function init(): void;
}
