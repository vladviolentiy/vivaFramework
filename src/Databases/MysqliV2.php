<?php

namespace VladViolentiy\VivaFramework\Databases;

use mysqli_result;
use mysqli_stmt;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class MysqliV2
{
    private \mysqli $db;

    /** @var array<string, mysqli_stmt>  */
    private array $stmtCache = [];

    /**
     * @var array{
     *     hostname:non-empty-string,
     *     username:non-empty-string,
     *     password:non-empty-string,
     *     database:non-empty-string,
     *     port:int<1,65535>
     * }
     */
    private array $masterInfo;
    private bool $isMaster = false;

    /**
     * @param non-empty-string $masterIp
     * @param non-empty-string $username
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @param int<1,65535> $port
     * @return void
     * @throws DatabaseException
     */
    private function openConnection(
        string $masterIp,
        string $username,
        string $password,
        string $database,
        int $port = 3306,
    ): void {
        $this->db = new \mysqli($masterIp, $username, $password, $database, $port);
        if ($this->db->errno !== 0) {
            throw new DatabaseException();
        }
    }

    private function initMaster(): void
    {
        if (!$this->isMaster) {
            $this->openConnection(
                $this->masterInfo['hostname'],
                $this->masterInfo['username'],
                $this->masterInfo['password'],
                $this->masterInfo['database'],
                $this->masterInfo['port'],
            );

            $this->isMaster = true;
        }
    }

    /**
     * @param non-empty-string $hostname
     * @param non-empty-string[] $slaveIps
     * @param non-empty-string $username
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @param int<1,65535> $port
     * @return void
     */
    protected function openMultiConnection(
        string $hostname,
        array $slaveIps,
        string $username,
        string $password,
        string $database,
        int $port = 3306,
    ): void {
        $this->masterInfo = [
            'hostname' => $hostname,
            'username' => $username,
            'password' => $password,
            'database' => $database,
            'port' => $port,
        ];

        $server = $slaveIps[array_rand($slaveIps)];
        $this->openConnection($server, $username, $password, $database, $port);
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
        $hash = hash('crc32c', $query);
        if (!isset($this->stmtCache[$hash])) {
            $pdo = $this->db->prepare($query);
            if ($pdo === false) {
                throw new DatabaseException();
            }
            $this->stmtCache[$hash] = $pdo;
        }

        return $this->stmtCache[$hash];
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
     * @return void
     * @throws DatabaseException
     */
    final protected function executePrepareBool(mysqli_stmt $prepare, array $params): void
    {
        if ($prepare->execute($params) === false) {
            throw new DatabaseException();
        }
    }

    final protected function insertId(): int
    {
        return (int) $this->db->insert_id;
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
}
