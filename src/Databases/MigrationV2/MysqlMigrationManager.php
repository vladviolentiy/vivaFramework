<?php

namespace VladViolentiy\VivaFramework\Databases\MigrationV2;

use VladViolentiy\VivaFramework\Databases\Interfaces\MigrationInterfaceV2;
use VladViolentiy\VivaFramework\Databases\MysqliV2;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;
use VladViolentiy\VivaFramework\Exceptions\MigrationException;

class MysqlMigrationManager extends MysqliV2 implements MigrationsClassInterfaceV2
{
    public function __construct(\mysqli $mysqli)
    {
        $this->setDb($mysqli);
    }

    private function checkIssetMigration(string $migration): bool
    {
        /** @var array{count:int<0,1>}|null $data */
        $data = $this->executeQuery('SELECT count(*) as count FROM migrations WHERE migration=?', [$migration])->fetch_array(MYSQLI_ASSOC);
        if ($data === null) {
            throw new MigrationException();
        }

        return $data['count'] > 0;
    }

    /**
     * @param class-string $migration
     * @return void
     * @throws DatabaseException
     */
    private function setMigration(string $migration): void
    {
        $this->executeQueryBool('INSERT INTO migrations(migration) VALUES (?)', [$migration]);
    }

    /**
     * @param non-empty-string $query
     * @return void
     * @throws DatabaseException
     */
    public function query(string $query): void
    {
        $this->executeQueryBoolRaw($query);
    }

    private function checkIssetMigrationTable(): bool
    {
        $count = $this->executeQueryRaw("show tables like 'migrations'")->num_rows;

        return $count > 0;
    }

    private function createMigrationTable(): void
    {
        $this->executeQueryBoolRaw('create table migrations
(
    migration varchar(256) not null primary key
)');
    }

    /**
     * @param class-string[] $classList
     * @return void
     * @throws DatabaseException
     * @throws MigrationException
     */
    public function migrate(array $classList): void
    {
        if (!$this->checkIssetMigrationTable()) {
            $this->createMigrationTable();
        }
        foreach ($classList as $item) {

            if ($this->checkIssetMigration($item)) {
                continue;
            }

            /** @var MigrationInterfaceV2 $migrationObject */
            $migrationObject = new $item($this);

            try {
                $related = $migrationObject->related();
                if (!empty($related)) {
                    self::migrate($related);
                }
                $this->beginTransaction();
                $migrationObject->up();
                $this->setMigration($item);
                $this->commit();
            } catch (\Exception $e) {
                $this->rollback();

                throw new MigrationException('Migrations exception. ' . $e->getMessage());
            }
        }
    }
}
