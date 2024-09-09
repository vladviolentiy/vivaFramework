<?php

namespace VladViolentiy\VivaFramework\Databases\Multireplica;

use mysqli;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class MysqliMultiReplica extends \VladViolentiy\VivaFramework\Databases\Mysqli
{
    /**
     * @var array{server:non-empty-string,login:non-empty-string,password:non-empty-string,database:non-empty-string}
     */
    protected array $masterInfo;

    private bool $isMaster = false;

    /**
     * @param non-empty-string $masterIp
     * @param non-empty-string[] $slaveIps
     * @param non-empty-string $login
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @return void
     */
    protected function initConnection(string $masterIp, array $slaveIps, string $login, string $password, string $database): void
    {
        $this->masterInfo = [
            "server" => $masterIp,
            "login" => $login,
            "password" => $password,
            "database" => $database
        ];

        $server = $slaveIps[array_rand($slaveIps)];
        $this->setDb(new mysqli($server, $login, $password, $database));
    }

    private function initMaster(): void
    {
        if (!$this->isMaster) {
            $this->setDb(new mysqli(
                $this->masterInfo['server'],
                $this->masterInfo['login'],
                $this->masterInfo['password'],
                $this->masterInfo['database'],
            ));

            $this->isMaster = true;
        }
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null> $params
     * @return void
     * @throws DatabaseException
     */
    protected function executeQueryBool(string $query, string $types, array $params): void
    {
        $this->initMaster();
        parent::executeQueryBool($query, $types, $params);
    }

    /**
     * @param non-empty-string $query
     * @return void
     * @throws DatabaseException
     */
    protected function executeQueryBoolRaw(string $query): void
    {
        $this->initMaster();
        parent::executeQueryBoolRaw($query);
    }

    public function beginTransaction(): void
    {
        $this->initMaster();
        parent::beginTransaction();
    }
}
