<?php

declare(strict_types=1);

namespace application\photo;

use application\login\loginControllerInterface;
use framework\database\databaseInterface;
use framework\image\imageInterface;
use framework\template\templateInterface;

class photo implements photoInterface
{

    private $template;

    private $database;

    private $config;

    private $imageHandler;

    private $login;


    public function __construct(templateInterface $template, databaseInterface $database,
                                imageInterface $imageHandler, loginControllerInterface $login,
                                array $config)
    {
        $this->template         = $template;
        $this->database         = $database;
        $this->imageHandler     = $imageHandler;
        $this->login            = $login;
        $this->config           = $config;
    }

    public function processImages(): void
    {
        $this->file2PSR7();
        $files = $_FILES;
        if(empty($files['photo']))
            return;
        $files          = $files['photo'];
        $sources        = [];
        $targets        = [];
        foreach($files AS $file){

            $filePath   = $file['tmp_name'];
            $MiMEType   = $this->imageHandler->getMiMEType($filePath);
            if(!$this->imageHandler->isImage($MiMEType)){
                trigger_error(sprintf('%s is not an image', $file['name']), E_USER_WARNING);
                return;
            }

            if(!$this->imageHandler->resize_image($filePath, 280, 280, true)){
                trigger_error(sprintf('%s is not resizable to (%d, %d)', $file['name'], 280, 280), E_USER_WARNING);
                return;
            }
            $extension      = $this->imageHandler->getExtension($MiMEType);
            $profileName    = $this->imageHandler->getRandomFileName($extension);
            $sources[]      = $filePath;
            $targets[]      = $profileName;
        }

        if(!$userID = $this->login->getUserID()){
            trigger_error(sprint('Could not get user ID in the method %s', __METHOD__), E_USER_WARNING);
            return;
        }


        $query      = 'INSERT INTO `photo`(`photoPath`, `photoUser`) VALUES (?, ?)';
        $type       = array('s', 'i');
        $this->database->startTransaction();
        foreach($targets AS $target){
            $data = array($target, $userID);
            if(false === $id = $this->database->query($query, $data, $type)){
                trigger_error($this->database->getDbError(), E_USER_WARNING);
                $this->database->rollback();
                return;
            }
        }

        $httpDocs           = $this->config['httpDocs'];
        $photoFolder        = $this->config['photo']['photoAssets'];
        $photoFolder        = $httpDocs.$photoFolder;
        if(!file_exists($photoFolder))
            mkdir($photoFolder);

        $sources = array_combine($sources, $targets);
        foreach($sources AS $source => $target){
            if(!move_uploaded_file($source, $photoFolder.$target)){
                trigger_error('Could not upload file', E_USER_WARNING);
                $this->database->rollback();
                return;
            }
        }
        trigger_error('The files have been uploaded successfully', E_USER_NOTICE);
        $this->database->commit();
    }



    public function file2PSR7() : void
    {
        // marshalling the images to be psr-7
        $copy_Files  = &$_FILES;
        foreach($copy_Files AS &$files){

            $keys           = array_keys($files);
            $names          = $files['name'];
            foreach($names AS $index => $value){
                if(!$value)
                    continue;

                $data = [];
                foreach($keys AS $key){
                    $data[$key] = $files[$key][$index];
                }
                $files[] = $data;
            }
            foreach($keys AS $key){
                unset($files[$key]);
            }
        }
        unset($files);

    }

    public function getPhotoData(int $limit): array
    {

        $photoFolder    = $this->config['photo']['photoAssets'];
        if(!$userID = $this->login->getUserID()){
            trigger_error('The username does not exist', E_USER_WARNING);
            return array();
        }

        $fields = Array(
            'photo' => 'CONCAT(?, `photoPath`)'
        );
        $table  = 'photo';
        $params = Array(
            'where' => Array('`photoUser` = ?'),
            'show'  => $limit
        );
        $data   = Array($photoFolder, $userID);
        $types  = Array('s','i');

        $query      = $this->database->getSelectSql($fields, $table, $params);

        if(false === $data = $this->database->query($query, $data, $types)){
            // user does not exist in the database
            if($this->database->getDbError())
                trigger_error($this->database->getDbError(), E_USER_WARNING);
            return array();
        }
        return $data;
    }

    public function insertPhotoData(string $photoPath, int $userID) : bool
    {

        $query      = "INSERT INTO `photo` (`photoPath`, `photoUser`) VALUES (?, ?)";
        $data       = Array($photoPath, $userID);
        $types      = Array('s', 'i');
        if(false === $id = $this->database->query($query, $data, $types)){
            trigger_error($this->database->getDbError(), E_USER_WARNING);
            return false;
        }

        return true;
    }

    public function drawThumbnail() : string
    {
        $data       = $this->getphotoData(4);
        $data       = array_merge($data, array_fill(0, abs(count($data)-4), []));
        $url        = $this->config['photo']['url'];

        $content    = Array('data' => $data, 'url' => $url);
        return $this->template->render(dirname(__DIR__) . '/template/thumbnail.php', $content);
    }


    public function drawPage(): string
    {
        $data       = $this->getphotoData(6);
        $data       = array_merge($data, array_fill(0, abs(count($data)-6), []));
        $url        = $this->config['photo']['url'];
        $content    = Array('data' => $data, 'url' => $url);
        return $this->template->render(dirname(__DIR__) . '/template/photo.php', $content);
    }
}
