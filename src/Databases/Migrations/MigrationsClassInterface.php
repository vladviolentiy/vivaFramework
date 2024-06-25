<?php

namespace VladViolentiy\VivaFramework\Databases\Migrations;

interface MigrationsClassInterface
{
    /**
     * @return class-string
     */
    public function getLastMigration(): string;

    /**
     * @param class-string $current
     * @return void
     */
    public function setCurrentMigration(string $current): void;

    /**
     * @param non-empty-string $query
     * @return void
     */
    public function query(string $query): void;

    public function checkIssetMigrationTable(): bool;

    public function createMigrationTable(): void;
}
