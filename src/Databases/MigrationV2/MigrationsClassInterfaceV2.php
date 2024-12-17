<?php

namespace VladViolentiy\VivaFramework\Databases\MigrationV2;

interface MigrationsClassInterfaceV2
{
    /**
     * @param non-empty-string $query
     * @return void
     */
    public function query(string $query): void;

    /**
     * @param class-string[] $classList
     * @return void
     */
    public function migrate(array $classList): void;
}
