<?php

namespace VladViolentiy\VivaFramework\Databases;

use VladViolentiy\VivaFramework\Databases\Interfaces\MigrationInterface;
use VladViolentiy\VivaFramework\Databases\Migrations\MigrationsClassInterface;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;
use VladViolentiy\VivaFramework\Exceptions\MigrationException;

abstract class DatabaseAbstract
{
    /**
     * @param non-empty-string $query
     * @return void
     */
    abstract protected function executeQueryBoolRaw(string $query): void;

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null> $params
     * @return void
     */
    abstract protected function executeQueryBool(string $query, string $types, array $params): void;

    abstract protected function insertId(): int;

    abstract public function beginTransaction(): void;

    abstract public function commit(): void;

    abstract public function rollback(): void;

    /**
     * @param MigrationsClassInterface $info
     * @param class-string[] $classList
     * @return void
     * @throws DatabaseException
     * @throws MigrationException
     */
    public static function migrator(MigrationsClassInterface $info, array $classList): void
    {
        if (!$info->checkIssetMigrationTable()) {
            $info->createMigrationTable();
        }
        $last = $info->getLastMigration();
        foreach ($classList as $item) {
            if ($last < $item) {
                /** @var MigrationInterface $migrationObject */
                $migrationObject = new $item($info);

                try {
                    $info->query('START TRANSACTION');
                    $migrationObject->init();
                    $info->setCurrentMigration($item);
                    $info->query('COMMIT');
                } catch (\Exception $e) {
                    $info->query('ROLLBACK');

                    throw new MigrationException('Migrations exception. ' . $e->getMessage());
                }
            }
        }

    }
}
