<?php

namespace VladViolentiy\VivaFramework\Databases;

use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class PDO
{
    private \PDO $db;

    /**
     * @param string $types
     * @return int
     */
    public function getType(string $types): int
    {
        return match ($types) {
            'i' => \PDO::PARAM_INT,
            default => \PDO::PARAM_STR,
        };
    }

    protected function setDb(\PDO $db): void
    {
        $this->db = $db;
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
        foreach ($params as $key => $param) {
            $type = $this->getType($types[$key]);
            $prepare->bindParam($key, $param, $type);
        }
        $prepare->execute();
        return $prepare;
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
        foreach ($params as $key => $param) {
            $type = $this->getType($types[$key]);
            $prepare->bindParam($key, $param, $type);
        }
        if ($prepare->execute() === false) throw new DatabaseException();
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
}