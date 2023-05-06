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
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param list<string|int|float|null> $params
     * @return \mysqli_result<int,string|int|float|null>
     * @throws DatabaseException
     */
    final protected function executeQuery(string $query, string $types, array $params):\mysqli_result{
        $prepare = $this->prepare($query);
        return $this->executePrepare($prepare,$types,$params);
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param list<string|int|float|null> $params
     * @throws DatabaseException
     */
    protected function executeQueryBool(string $query, string $types, array $params):void{
        $prepare = $this->prepare($query);
        $this->executePrepareBool($prepare,$types,$params);
    }

    /**
     * @param non-empty-string $query
     * @return void
     * @throws DatabaseException
     */
    protected function executeQueryBoolRaw(string $query):void{
        $prepare = $this->prepare($query);
        if($prepare->execute()===false) throw new DatabaseException();
    }

    /**
     * @param non-empty-string $query
     * @return \mysqli_result
     * @throws DatabaseException
     */
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

    /**
     * @param \mysqli_stmt $prepare
     * @param string $types
     * @param list<string|int|float|null> $params
     * @return \mysqli_result
     * @throws DatabaseException
     */
    final protected function executePrepare(\mysqli_stmt $prepare, string $types, array $params):\mysqli_result{
        $prepare->bind_param($types,...$params);
        if($prepare->execute()===false) throw new DatabaseException();
        $result =  $prepare->get_result();
        if($result===false) throw new DatabaseException();
        return $result;
    }

    /**
     * @param \mysqli_stmt $prepare
     * @param string $types
     * @param list<string|int|float|null> $params
     * @return void
     */
    final protected function executePrepareBool(\mysqli_stmt $prepare, string $types, array $params):void{
        $prepare->bind_param($types,...$params);
        if($prepare->execute()===false) throw new DatabaseException();
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