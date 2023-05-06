<?php

namespace VladViolentiy\VivaFramework\Databases;

abstract class DatabaseAbstract
{
    /**
     * @param non-empty-string $query
     * @return void
     */
    abstract protected function executeQueryBoolRaw(string $query):void;

    /**
     * @param non-empty-string $query
     * @param non-empty-string $types
     * @param non-empty-list<string|int|float|null> $params
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