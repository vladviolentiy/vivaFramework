<?php

namespace VladViolentiy\VivaFramework\Databases;

use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class Mysqli
{
    private \mysqli $db;


    final protected function setDb(string $masterIp, string $login, string $password, string $database): void{
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
    final protected function executeQueryBool(string $query, string $types, array $params):void{
        $prepare = $this->prepare($query);
        $prepare->bind_param($types,...$params);
        if($prepare->execute()===false) throw new DatabaseException();
    }

    final protected function executeQueryBoolRaw(string $query):void{
        $prepare = $this->prepare($query);
        if($prepare->execute()===false) throw new DatabaseException();
    }

    final protected function prepare(string $query):\mysqli_stmt{
        $pdo = $this->db->prepare($query);
        if($pdo===false) throw new DatabaseException();
        return $pdo;
    }

    final protected function insertId():int{
        return (int)$this->db->insert_id;
    }
}