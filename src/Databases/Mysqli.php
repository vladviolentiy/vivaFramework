<?php

namespace VladViolentiy\VivaFramework\Databases;

use VladViolentiy\VivaFramework\Databases\Interfaces\MigrationInterface;
use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class Mysqli extends DatabaseAbstract
{
    private \mysqli $db;


    final protected function setDb(\mysqli $db): void{
        $this->db = $db;
    }

    final protected function openConnection(string $masterIp, string $login, string $password, string $database): void{
        $this->db = new \mysqli($masterIp,$login,$password,$database);
        if($this->db->errno!==0) throw new DatabaseException();
    }

    /**
     * @param string $query
     * @param string $types
     * @param array<int,string|int|float|null> $params
     * @return \mysqli_result<int,string|int|float|null>
     * @throws DatabaseException
     */
    final protected function executeQuery(string $query, string $types, array $params):\mysqli_result{
        $prepare = $this->prepare($query);
        $prepare->bind_param($types,...$params);
        $prepare->execute();
        $result = $prepare->get_result();
        if($result===false) throw new DatabaseException();
        return $result;
    }

    /**
     * @param string $query
     * @param string $types
     * @param array<int,string|int|float|null> $params
     * @throws DatabaseException
     */
    protected function executeQueryBool(string $query, string $types, array $params):void{
        $prepare = $this->prepare($query);
        $prepare->bind_param($types,...$params);
        if($prepare->execute()===false) throw new DatabaseException();
    }

    final protected function executeQueryBoolRaw(string $query):void{
        $prepare = $this->prepare($query);
        if($prepare->execute()===false) throw new DatabaseException();
    }

    final protected function executeQueryRaw(string $query):\mysqli_result{
        $prepare = $this->prepare($query);
        if($prepare->execute()===false) throw new DatabaseException();
        $result = $prepare->get_result();
        if($result===false) throw new DatabaseException();
        return $result;
    }

    final protected function prepare(string $query):\mysqli_stmt{
        $pdo = $this->db->prepare($query);
        if($pdo===false) throw new DatabaseException();
        return $pdo;
    }

    final protected function insertId():int{
        return (int)$this->db->insert_id;
    }

    /**
     * @param class-string[] $list
     * @return void
     */
    public function takeMigration(array $list):void{
        foreach ($list as $migration) {
            /** @var MigrationInterface $item */
            $item = new $migration($this->db);
            $item->init();
        }
    }
}