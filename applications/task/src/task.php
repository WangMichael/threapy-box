<?php

declare(strict_types=1);

namespace application\task;

use framework\container\containerInterface;

class task implements taskInterface
{


    private $container;

    public function __construct(containerInterface $container)
    {
        $this->container = $container;
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
        $userID = $this->container->get('login')->getUserID();
        if(!$userID){
            trigger_error(sprintf('The user does not exist in %s', __METHOD__), E_USER_WARNING);
            return;
        }

        $query      = 'INSERT INTO `task`(`taskDescription`, `taskStatus`, `taskUser`) VALUES (?,?,?)';
        $database   = $this->container->get('database');
        $database->startTransaction();
        foreach($data AS $task){
            $data   = array($task['description'], $task['status'], $userID);
            $type   = array('s', 'i', 'i');
            if(false === $id = $database->query($query, $data, $type)){
                trigger_error($database->getDbError(), E_USER_WARNING);
                $database->rollback();
                return;
            }

        }
        $database->commit();

    }


    public function updateTaskData(array $data) : void
    {
        $userID = $this->container->get('login')->getUserID();
        if(!$userID){
            trigger_error(sprintf('The user does not exist in %s', __METHOD__), E_USER_WARNING);
            return;
        }

        $query = 'UPDATE `task` SET `taskDescription`= ?,`taskStatus`= ? WHERE `taskID` = ? AND `taskUser` = ?';
        $database   = $this->container->get('database');
        $database->startTransaction();
        foreach($data AS $task){
            $data   = array($task['description'], (int)$task['status'], (int)$task['id'], $userID);
            $type   = array('s', 'i', 'i', 'i');
            if(false === $id = $database->query($query, $data, $type)){
                trigger_error($database->getDbError(), E_USER_WARNING);
                $database->rollback();
                return;
            }

        }
        $database->commit();
    }

    public function getTaskData(int $limit = null): array
    {

        $userID = $this->container->get('login')->getUserID();
        if(!$userID){
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

        $database   = $this->container->get('database');
        $query      = $database->getSelectSql($fields, $table, $params);
        if(false === $data = $database->query($query, $data, $type)){
            if($database->getDbError())
                trigger_error($database->getDbError(), E_USER_WARNING);
            return array();
        }

        return $data;
    }

    public function drawThumbnail() : string
    {
        $data       = $this->getTaskData(3);
        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__) . '/template/thumbnail.php', array('data' => $data));
    }


    public function drawPage(): string
    {
        $data       = $this->getTaskData();
        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__) . '/template/task.php', array('data' => $data));
    }
}
