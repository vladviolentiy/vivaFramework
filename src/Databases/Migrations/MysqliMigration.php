<?php

namespace VladViolentiy\VivaFramework\Databases\Migrations;

use VladViolentiy\VivaFramework\Databases\Mysqli;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;
use VladViolentiy\VivaFramework\Exceptions\MigrationException;

class MysqliMigration extends Mysqli
{
    public function __construct(\mysqli $mysqli)
    {
        $this->setDb($mysqli);
    }

    public function getLastMigration():string{
        /** @var array{current:non-empty-string}|null $i */
        $i = $this->executeQueryRaw("SELECT current FROM migration ")->fetch_array(MYSQLI_ASSOC);
        if($i===null) throw new MigrationException();
        return $i['current'];
    }

    /**
     * @param class-string $current
     * @return void
     * @throws DatabaseException
     */
    public function setCurrentMigration(string $current):void{
        $this->executeQueryBool("UPDATE migration set current=?","s",[$current]);
    }
}