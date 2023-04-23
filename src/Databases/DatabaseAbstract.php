<?php

namespace VladViolentiy\VivaFramework\Databases;

abstract class DatabaseAbstract
{
    abstract protected function executeQueryBoolRaw(string $query):void;

    /**
     * @param string $query
     * @param string $types
     * @param array<int,string|int|float|null> $params
     * @return void
     */
    abstract protected function executeQueryBool(string $query, string $types, array $params):void;
    /**
     * @param class-string[] $list
     * @return void
     */
    abstract public function takeMigration(array $list):void;

    abstract protected function insertId():int;
}