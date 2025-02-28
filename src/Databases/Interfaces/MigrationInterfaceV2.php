<?php

namespace VladViolentiy\VivaFramework\Databases\Interfaces;

use VladViolentiy\VivaFramework\Databases\MigrationV2\MigrationsClassInterfaceV2;

interface MigrationInterfaceV2
{
    public function __construct(MigrationsClassInterfaceV2 $migrator);

    public function up(): void;

    /**
     * @return list<class-string>
     */
    public function related(): array;
}
