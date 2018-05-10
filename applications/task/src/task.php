<?php

declare(strict_types=1);

namespace application\task;

use application\login\loginControllerInterface;
use framework\database\databaseInterface;
use framework\template\templateInterface;

class task implements taskInterface
{

    private $template;

    private $login;

    private $config;

    private $database;

    public function __construct(templateInterface $template, databaseInterface $database, loginControllerInterface $login, array $config)
    {
        $this->template = $template;
        $this->login    = $login;
        $this->config   = $config;
        $this->database = $database;
    }

    public function processTask()
    {
        if(!empty($_POST['update']))
            $this->updateTaskData($_POST['update']);

        if(!empty($_POST['insert']))
            $this->insertTaskData($_POST['insert']);

    }


    public function insertTaskData(array $data) : void
    {

        if(!$userID = $this->login->getUserID()){
            trigger_error(sprintf('The user does not exist in %s', __METHOD__), E_USER_WARNING);
            return;
        }

        $query      = 'INSERT INTO `task`(`taskDescription`, `taskStatus`, `taskUser`) VALUES (?,?,?)';
        $this->database->startTransaction();
        foreach($data AS $task){
            $data   = array($task['description'], $task['status'], $userID);
            $type   = array('s', 'i', 'i');
            if(false === $id = $this->database->query($query, $data, $type)){
                trigger_error($this->database->getDbError(), E_USER_WARNING);
                $this->database->rollback();
                return;
            }

        }
        $this->database->commit();

    }


    public function updateTaskData(array $data) : void
    {

        if(!$userID = $this->login->getUserID()){
            trigger_error(sprintf('The user does not exist in %s', __METHOD__), E_USER_WARNING);
            return;
        }

        $query = 'UPDATE `task` SET `taskDescription`= ?,`taskStatus`= ? WHERE `taskID` = ? AND `taskUser` = ?';
        $this->database->startTransaction();
        foreach($data AS $task){
            $data   = array($task['description'], (int)$task['status'], (int)$task['id'], $userID);
            $type   = array('s', 'i', 'i', 'i');
            if(false === $id = $this->database->query($query, $data, $type)){
                trigger_error($this->database->getDbError(), E_USER_WARNING);
                $this->database->rollback();
                return;
            }

        }
        $this->database->commit();
    }

    public function getTaskData(int $limit = null): array
    {

        if(!$userID = $this->login->getUserID()){
            trigger_error(sprintf('The user does not exist in %s', __METHOD__), E_USER_WARNING);
            return array();
        }

        $fields = Array(
            'id'          => 'taskID',
            'description' => 'taskDescription',
            'status'      => 'taskStatus'
        );

        $table  = 'task';
        $params = Array(
            'where' => Array('`taskUser` = ?')
        );
        if(!is_null($limit))
            $params['show'] = $limit;
        $data   = array($userID);
        $type   = array('i');
        $query      = $this->database->getSelectSql($fields, $table, $params);
        if(false === $data = $this->database->query($query, $data, $type)){
            if($this->database->getDbError())
                trigger_error($this->database->getDbError(), E_USER_WARNING);
            return array();
        }

        return $data;
    }

    public function drawThumbnail() : string
    {
        $data       = $this->getTaskData(3);
        return $this->template->render(dirname(__DIR__) . '/template/thumbnail.php', array('data' => $data));
    }


    public function drawPage(): string
    {
        $data       = $this->getTaskData();
        return $this->template->render(dirname(__DIR__) . '/template/task.php', array('data' => $data));
    }
}
