<?php

namespace VladViolentiy\VivaFramework\Databases\Multireplica;

use VladViolentiy\VivaFramework\Exceptions\DatabaseException;

abstract class Mysqli
{
    /**
     * @var array{server:string,login:string,password:string,database:string}
     */
    protected array $masterInfo;
    private \mysqli $db;

    /**
     * @param non-empty-string $masterIp
     * @param non-empty-string[] $slaveIps
     * @param non-empty-string $login
     * @param non-empty-string $password
     * @param non-empty-string $database
     * @return void
     */
    protected function initDb(string $masterIp, array $slaveIps, string $login, string $password, string $database): void{
        $this->masterInfo = [
            "server"=>$masterIp,
            "login"=>$login,
            "password"=>$password,
            "database"=>$database
        ];

        $server = $slaveIps[array_rand($slaveIps)];
        $this->db = new \mysqli($server,$login,$password,$database);
    }

    private bool $isMaster = false;

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param list<string|int|float|null> $params
     * @return \mysqli_result
     * @throws DatabaseException
     */
    final protected function executeQuery(string $query, string $types, array $params):\mysqli_result{
        $prepare = $this->prepare($query);
        $prepare->bind_param($types,...$params);
        $prepare->execute();
        $result = $prepare->get_result();
        if($result===false) throw new \VladViolentiy\VivaFramework\Exceptions\DatabaseException();
        return $result;
    }

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float> $params
     * @return void
     * @throws DatabaseException
     */
    protected function executeQueryBool(string $query, string $types, array $params):void{
        if(!$this->isMaster){
            $this->db = new \mysqli(
                $this->masterInfo['server'],
                $this->masterInfo['login'],
                $this->masterInfo['password'],
                $this->masterInfo['database'],
            );
            $this->isMaster = true;
        }
        $prepare = $this->prepare($query);
        $prepare->bind_param($types,...$params);
        if($prepare->execute()===false) throw new DatabaseException();
    }

    final protected function prepare(string $query):\mysqli_stmt{
        $pdo = $this->db->prepare($query);
        if($pdo===false) throw new \VladViolentiy\VivaFramework\Exceptions\DatabaseException();
        return $pdo;
    }

    final protected function insertId():int{
        return (int)$this->db->insert_id;
    }
}