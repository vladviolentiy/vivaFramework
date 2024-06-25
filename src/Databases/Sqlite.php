<?php

namespace VladViolentiy\VivaFramework\Databases;

use VladViolentiy\VivaFramework\Databases\Migrations\SqliteMigration;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;
use VladViolentiy\VivaFramework\Exceptions\MigrationException;

abstract class Sqlite extends DatabaseAbstract
{
    private \SQLite3 $db;

    /**
     * @param string $types
     * @return int
     */
    private function getType(string $types): int
    {
        return match ($types) {
            'i' => SQLITE3_INTEGER,
            'd' => SQLITE3_FLOAT,
            default => SQLITE3_TEXT
        };
    }

    final protected function setDb(\SQLite3 $db): void
    {
        $this->db = $db;
    }

    /**
     * @param non-empty-string $file
     * @return void
     * @throws DatabaseException
     */
    final protected function openConnection(string $file): void
    {
        $this->db = new \SQLite3($file);
        if($this->db->lastErrorCode() !== 0) {
            throw new DatabaseException();
        }
    }

    protected function executeQueryBool(string $query, string $types, array $params): void
    {
        $prepare = $this->prepare($query);
        $this->executePrepare($prepare, $types, $params);
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null> $params
     * @return \SQLite3Result
     * @throws DatabaseException
     */
    protected function executeQuery(string $query, string $types, array $params): \SQLite3Result
    {
        $prepare = $this->prepare($query);
        return $this->executePrepare($prepare, $types, $params);
    }

    /**
     * @param non-empty-string $query
     * @return void
     * @throws DatabaseException
     */
    protected function executeQueryBoolRaw(string $query): void
    {
        $prepare = $this->prepare($query);
        $result = $prepare->execute();
        if($result === false) {
            throw new DatabaseException();
        }
    }

    /**
     * @param non-empty-string $query
     * @return \SQLite3Result
     * @throws DatabaseException
     */
    protected function executeQueryRaw(string $query): \SQLite3Result
    {
        $prepare = $this->prepare($query);
        $result = $prepare->execute();
        if($result === false) {
            throw new DatabaseException();
        }
        return  $result;
    }

    /**
     * @param non-empty-string $query
     * @return \SQLite3Stmt
     * @throws DatabaseException
     */
    protected function prepare(string $query): \SQLite3Stmt
    {
        $i = $this->db->prepare($query);
        if($i === false) {
            throw new DatabaseException();
        }
        return $i;
    }

    /**
     * @param \SQLite3Stmt $prepare
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null> $params
     * @return \SQLite3Result
     * @throws DatabaseException
     */
    protected function executePrepare(\SQLite3Stmt $prepare, string $types, array $params): \SQLite3Result
    {
        foreach ($params as $key => $param) {
            $type = $this->getType($types[$key]);
            $prepare->bindParam($key, $param, $type);
        }
        $result = $prepare->execute();
        if($result === false) {
            throw new DatabaseException();
        }
        return  $result;
    }

    final protected function insertId(): int
    {
        return $this->db->lastInsertRowID();
    }

    public function beginTransaction(): void
    {
    }

    public function commit(): void
    {
    }

    public function rollback(): void
    {
    }

    /**
     * @param SqliteMigration $object
     * @param class-string[] $classes
     * @return void
     * @throws DatabaseException
     * @throws MigrationException
     */
    public static function checkMigration(SqliteMigration $object, array $classes): void
    {
        self::migrator($object, $classes);
    }
}
