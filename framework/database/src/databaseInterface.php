<?php

declare(strict_types=1);

namespace framework\database;

interface databaseInterface{

    public function getConnection() : \mysqli;

    public function getSelectSql(array $fields, string $table, array $params = array()) : string;

    public function query(string $query, array $data = Array(), array $types = array());

    public function startTransaction();

    public function commit();

    public function rollback();

    public function getDbError();
}