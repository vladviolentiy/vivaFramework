<?php

namespace VladViolentiy\VivaFramework\Databases;

use mysqli_result;
use mysqli_stmt;
use VladViolentiy\VivaFramework\Databases\Migrations\MysqliMigration;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;
use VladViolentiy\VivaFramework\Exceptions\MigrationException;

abstract class MysqliV2
{
    private \mysqli $db;

    /**
     * @var array{
     *     server:non-empty-string,
     *     login:non-empty-string,
     *     password:non-empty-string,
     *     database:non-empty-string,
     *     port:int<1,65535>
     * }
     */
    protected array $masterInfo;

    private bool $isMaster = false;

    /**
     * @param non-empty-string $masterIp
     * @param non-empty-string $login
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @param int<1,65535> $port
     * @return void
     * @throws DatabaseException
     */
    private function openConnection(string $masterIp, string $login, string $password, string $database, int $port = 3306): void
    {
        $this->db = new \mysqli($masterIp, $login, $password, $database, $port);
        if ($this->db->errno !== 0) {
            throw new DatabaseException();
        }
    }

    private function initMaster(): void
    {
        if (!$this->isMaster) {
            $this->openConnection(
                $this->masterInfo['server'],
                $this->masterInfo['login'],
                $this->masterInfo['password'],
                $this->masterInfo['database'],
                $this->masterInfo['port'],
            );

            $this->isMaster = true;
        }
    }

    /**
     * @param non-empty-string $masterIp
     * @param non-empty-string[] $slaveIps
     * @param non-empty-string $login
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @param int<1,65535> $port
     * @return void
     */
    protected function openMultiConnection(
        string $masterIp,
        array $slaveIps,
        string $login,
        string $password,
        string $database,
        int $port = 3306,
    ): void {
        $this->masterInfo = [
            'server' => $masterIp,
            'login' => $login,
            'password' => $password,
            'database' => $database,
            'port' => $port,
        ];

        $server = $slaveIps[array_rand($slaveIps)];
        $this->openConnection($server, $login, $password, $database);
    }

    final protected function setDb(\mysqli $db): void
    {
        $this->isMaster = true;
        $this->db = $db;
    }

    /**
     * @param non-empty-string $masterIp
     * @param non-empty-string $login
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @param int<1,65535> $port
     * @return void
     * @throws DatabaseException
     */
    final protected function openSingleConnection(
        string $masterIp,
        string $login,
        string $password,
        string $database,
        int $port = 3306,
    ): void {
        $this->isMaster = true;
        $this->openConnection($masterIp, $login, $password, $database, $port);
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-list<string|int|float|null> $params
     * @return mysqli_result<int,string|int|float|null>
     * @throws DatabaseException
     */
    final protected function executeQuery(string $query, array $params): mysqli_result
    {
        $prepare = $this->prepare($query);

        return $this->executePrepare($prepare, $params);
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-list<string|int|float|null> $params
     * @return int
     * @throws DatabaseException
     */
    protected function executeQueryBool(string $query, array $params): int
    {
        $this->initMaster();
        $prepare = $this->prepare($query);
        $this->executePrepareBool($prepare, $params);

        return $this->insertId();

    }

    /**
     * @param non-empty-string $query
     * @return int
     * @throws DatabaseException
     */
    protected function executeQueryBoolRaw(string $query): int
    {
        $this->initMaster();
        $result = $this->db->query($query);
        if ($result === false) {
            throw new DatabaseException();
        }

        return $this->insertId();
    }

    /**
     * @param non-empty-string $query
     * @return mysqli_result
     * @throws DatabaseException
     */
    final protected function executeQueryRaw(string $query): mysqli_result
    {
        $result = $this->db->query($query);
        if (is_bool($result)) {
            throw new DatabaseException();
        }

        return $result;
    }

    /**
     * @param non-empty-string $query
     * @return mysqli_stmt
     * @throws DatabaseException
     */
    final protected function prepare(string $query): mysqli_stmt
    {
        $pdo = $this->db->prepare($query);
        if ($pdo === false) {
            throw new DatabaseException();
        }

        return $pdo;
    }

    /**
     * @param mysqli_stmt $prepare
     * @param non-empty-list<string|int|float|null> $params
     * @return mysqli_result
     * @throws DatabaseException
     */
    final protected function executePrepare(mysqli_stmt $prepare, array $params): mysqli_result
    {
        if ($prepare->execute($params) === false) {
            throw new DatabaseException();
        }
        $result =  $prepare->get_result();
        if ($result === false) {
            throw new DatabaseException();
        }

        return $result;
    }

    /**
     * @param mysqli_stmt $prepare
     * @param non-empty-list<string|int|float|null> $params
     * @return int
     * @throws DatabaseException
     */
    final protected function executePrepareBool(mysqli_stmt $prepare, array $params): int
    {
        if ($prepare->execute($params) === false) {
            throw new DatabaseException();
        }

        return $this->insertId();
    }

    final protected function insertId(): int
    {
        return (int) $this->db->insert_id;
    }

    final protected function affectedRows(): int
    {
        return (int) $this->db->affected_rows;
    }

    public function beginTransaction(): void
    {
        $this->initMaster();
        $this->db->autocommit(false);
        $this->db->begin_transaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollback(): void
    {
        $this->db->rollback();
    }

    /**
     * @param MysqliMigration $object
     * @param class-string[] $classes
     * @return void
     * @throws DatabaseException
     * @throws MigrationException
     */
    public static function checkMigration(MysqliMigration $object, array $classes): void
    {
        DatabaseAbstract::migrator($object, $classes);
    }
}
