<?php

namespace VladViolentiy\VivaFramework\Databases;

use PDOStatement;
use VladViolentiy\VivaFramework\Databases\Interfaces\MigrationInterface;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class PDO extends DatabaseAbstract
{
    private \PDO $db;

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

    /**
     * @param non-empty-string $dsn
     * @param non-empty-string $login
     * @param non-empty-string $password
     * @return void
     */
    final protected function openConnection(string $dsn, string $login, string $password): void
    {
        $this->db = new \PDO($dsn, $login, $password);
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null>|non-empty-array<string,string|int|float|null> $params
     * @return PDOStatement
     * @throws DatabaseException
     */
    final protected function executeQuery(string $query, string $types, array $params): PDOStatement
    {
        $prepare = $this->prepare($query);
        return $this->executePrepare($prepare,$types,$params);
    }

    final protected function executeQueryBool(string $query, string $types, array $params): void
    {
        $prepare = $this->prepare($query);
        $this->executePrepare($prepare,$types,$params);
    }

    final protected function executeQueryBoolRaw(string $query): void
    {
        $prepare = $this->prepare($query);
        if ($prepare->execute() === false) throw new DatabaseException();
    }

    /**
     * @param non-empty-string $query
     * @return PDOStatement
     * @throws DatabaseException
     */
    final protected function executeQueryRaw(string $query): PDOStatement
    {
        $prepare = $this->prepare($query);
        if ($prepare->execute() === false) throw new DatabaseException();
        return $prepare;
    }

    /**
     * @param non-empty-string $query
     * @return PDOStatement
     * @throws DatabaseException
     */
    final protected function prepare(string $query): PDOStatement
    {
        $pdo = $this->db->prepare($query);
        if ($pdo === false) throw new DatabaseException();
        return $pdo;
    }

    /**
     * @param PDOStatement $prepare
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null>|non-empty-array<string,string|int|float|null> $params
     * @return PDOStatement
     * @throws DatabaseException
     */
    final protected function executePrepare(PDOStatement $prepare, string $types, array $params): PDOStatement
    {
        $iterator = 0;
        foreach ($params as $key => $param) {
            $type = $this->getType($types[$iterator]);
            if(is_int($key)){
                $prepare->bindValue($iterator+1, $param, $type);
            } else {
                $prepare->bindValue($key, $param, $type);
            }
            $iterator++;
        }
        if ($prepare->execute() === false) throw new DatabaseException();
        return $prepare;
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