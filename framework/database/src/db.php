<?php

declare(strict_types=1);

namespace framework\database;

class db implements databaseInterface {


    private $connection;

    private $dbError;

    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): \mysqli
    {
       return $this->connection;
    }

    public function commit()
    {
        return mysqli_commit($this->connection);
    }

    public function rollback()
    {
        return mysqli_rollback($this->connection);
    }

    public function startTransaction()
    {
       return mysqli_begin_transaction($this->connection);
    }

    public function getDbError() : string
    {
        if ($this->dbError)
            return $this->dbError;

        if (!empty($this->connection))
            return mysqli_error($this->connection);

        return mysqli_connect_error();
    }

    public function getSelectSql(array $fields, string $table, array $params = array()) : string
    {

        // normalise params
        $params = array_merge(Array(
            'where' => false,
            'show' => false,
        ), $params);

        // fields
        $fieldSql = Array();
        foreach ($fields AS $key => $value) {
            $fieldSql[] = $value.' AS '.'`'.$key.'`';
        }

        // build query
        $query = 'SELECT '.implode(', ', $fieldSql);
        $query .= ' FROM '.$table;
        if($params['where']){
            if(is_string($params['where']))
                $params['where'] = array($params['where']);
            $query .= ' WHERE '.implode(', ', $params['where']);
        }
        if($params['show'])
            $query .= ' LIMIT '.$params['show'];


        return $query;
    }


    public function query(string $query, array $data = Array(), array $types = array()) {

        if($data && count($data) != count($types)){
            trigger_error('The number of data parameters does not match the number of type parameters', E_USER_WARNING);
            return false;
        }

        // prepare query
        if(false === $stmt = mysqli_prepare($this->connection, $query)){
            trigger_error($this->getDbError(), E_USER_WARNING);
            return false;
        }

        // bind the statement
        if ($data) {

            $args = Array($stmt, implode('', $types));
            $null = null;
            foreach ($data AS $key => $value) {
                if ($types[$key] == 'b')
                    $args[] = &$null;
                else
                    $args[] = &$data[$key]; // must be referenced for PHP 5.3+
            }
            call_user_func_array('mysqli_stmt_bind_param', $args);

            // send binary data
            foreach ($data AS $key => $value) {
                if ($types[$key] == 'b' && !is_null($data[$key])) {
                    mysqli_stmt_send_long_data($stmt, $key, $value);
                    $data[$key] = '0x' . (is_null($value) ? '00' : implode('', unpack('H*', $value)));
                }
            }
        }


        // execute statement
        if(!mysqli_stmt_execute($stmt)){
            trigger_error($this->getDbError(), E_USER_WARNING);
            return false;
        }


        // insert query: return last insert ID
        if (strpos($query, 'INSERT ') === 0)
            return mysqli_stmt_insert_id($stmt);

        // update and delete queries: return affected rows
        if (strpos($query, 'UPDATE ') === 0 || strpos($query, 'DELETE ') === 0)
            return ($count = mysqli_stmt_affected_rows($stmt)) !== -1 ? $count : false;

        // select query
        $data = mysqli_fetch_all($stmt->get_result(), MYSQLI_ASSOC);
        return $data ?: false;

    }

}