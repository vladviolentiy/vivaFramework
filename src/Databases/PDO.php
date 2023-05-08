<?php

namespace VladViolentiy\VivaFramework\Databases;

use VladViolentiy\VivaFramework\Databases\Interfaces\MigrationInterface;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class PDO extends DatabaseAbstract
{
    private \PDO $db;

    /**
     * @param string $types
     * @return int
     */
    private function getType(string $types): int
    {
        return match ($types) {
            'i' => \PDO::PARAM_INT,
            default => \PDO::PARAM_STR,
        };
    }

    final protected function setDb(\PDO $db): void
    {
        $this->db = $db;
    }

    final protected function openConnection(string $dsn, string $login, string $password): void
    {
        $this->db = new \PDO($dsn, $login, $password);
    }

    /**
     * @param string $query
     * @param string $types
     * @param array<int,string|int|float|null> $params
     * @return \PDOStatement
     * @throws DatabaseException
     */
    final protected function executeQuery(string $query, string $types, array $params): \PDOStatement
    {
        $prepare = $this->prepare($query);
        return $this->executePrepare($prepare,$types,$params);
    }

    /**
     * @param string $query
     * @param string $types
     * @param list<string|int|float|null> $params
     * @throws DatabaseException
     */
    final protected function executeQueryBool(string $query, string $types, array $params): void
    {
        $prepare = $this->prepare($query);
        $this->executePrepareBool($prepare,$types,$params);
    }

    final protected function executeQueryBoolRaw(string $query): void
    {
        $prepare = $this->prepare($query);
        if ($prepare->execute() === false) throw new DatabaseException();
    }

    final protected function prepare(string $query): \PDOStatement
    {
        $pdo = $this->db->prepare($query);
        if ($pdo === false) throw new DatabaseException();
        return $pdo;
    }

    /**
     * @param \PDOStatement $prepare
     * @param string $types
     * @param list<string|int|float|null> $params
     * @return \PDOStatement
     * @throws DatabaseException
     */
    final protected function executePrepare(\PDOStatement $prepare, string $types, array $params):\PDOStatement
    {
        foreach ($params as $key => $param) {
            $type = $this->getType($types[$key]);
            $prepare->bindParam($key, $param, $type);
        }
        if ($prepare->execute() === false) throw new DatabaseException();
        return $prepare;
    }

    /**
     * @param \PDOStatement $prepare
     * @param string $types
     * @param list<string|int|float|null> $params
     * @return void
     * @throws DatabaseException
     */
    final protected function executePrepareBool(\PDOStatement $prepare, string $types, array $params):void
    {
        foreach ($params as $key => $param) {
            $type = $this->getType($types[$key]);
            $prepare->bindParam($key, $param, $type);
        }
        if ($prepare->execute() === false) throw new DatabaseException();
    }

    final protected function insertId(): int
    {
        return (int)$this->db->lastInsertId();
    }

    public function takeMigration(array $list): void
    {
        foreach ($list as $migration) {
            /** @var MigrationInterface $item */
            $item = new $migration($this->db);
            $item->init();
        }
    }

    public function beginTransaction():void{
        $this->db->setAttribute(\PDO::ATTR_AUTOCOMMIT,0);
        $this->db->beginTransaction();
    }

    public function commit():void{
        $this->db->commit();
    }
}