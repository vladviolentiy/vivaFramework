<?php

namespace VladViolentiy\VivaFramework\Databases\Migrations;

use VladViolentiy\VivaFramework\Databases\Migrations\MigrationsClassInterface;
use VladViolentiy\VivaFramework\Databases\PDO;
use VladViolentiy\VivaFramework\Exceptions\MigrationException;

class PdoMigration extends PDO implements MigrationsClassInterface
{
        public function getLastMigration(): string
    {
        /** @var array{current:class-string}|null $i */
        $i = $this->executeQueryRaw("SELECT current FROM migration ")->fetch(\PDO::FETCH_ASSOC);
        if($i===null) throw new MigrationException();
        return $i['current'];
    }

    public function setCurrentMigration(string $current): void
    {
        $this->executeQueryBool("UPDATE migration set current=?","s",[$current]);
    }

    public function query(string $query): void
    {
        $this->executeQueryBoolRaw($query);
    }
}