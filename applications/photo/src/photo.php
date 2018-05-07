<?php

declare(strict_types=1);

namespace application\photo;

use framework\container\containerInterface;
use framework\image\image;
use framework\image\imageInterface;

class photo implements photoInterface
{


    private $container;

    public function __construct(containerInterface $container)
    {
        $this->container = $container;
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
        $imageHandler   = $this->container->get('image');
        foreach($files AS $file){

            $filePath   = $file['tmp_name'];
            $MiMEType   = $imageHandler->getMiMEType($filePath);
            if(!$imageHandler->isImage($MiMEType)){
                trigger_error(sprintf('%s is not an image', $file['name']), E_USER_WARNING);
                return;
            }

            if(!$imageHandler->resize_image($filePath, 280, 280, true)){
                trigger_error(sprintf('%s is not resizable to (%d, %d)', $file['name'], 280, 280), E_USER_WARNING);
                return;
            }
            $extension      = $imageHandler->getExtension($MiMEType);
            $profileName    = $imageHandler->getRandomFileName($extension);
            $sources[]      = $filePath;
            $targets[]      = $profileName;
        }

        $login    = $this->container->get('login');
        $userID   = $login->getUserID();
        if(!$userID){
            trigger_error(sprint('Could not get user ID in the method %s', __METHOD__), E_USER_WARNING);
            return;
        }

        $database   = $this->container->get('database');
        $query      = 'INSERT INTO `photo`(`photoPath`, `photoUser`) VALUES (?, ?)';
        $type       = array('s', 'i');
        $database->startTransaction();
        foreach($targets AS $target){
            $data = array($target, $userID);
            if(false === $id = $database->query($query, $data, $type)){
                trigger_error($database->getDbError(), E_USER_WARNING);
                $database->rollback();
                return;
            }
        }

        $aggregateConfig    = $this->container->get('aggregateConfig');
        $httpDocs           = $aggregateConfig->getConfig('httpDocs');
        $photoFolder        = $aggregateConfig->getConfig('photo', 'photoAssets');
        $photoFolder        = $httpDocs.$photoFolder;
        if(!file_exists($photoFolder))
            mkdir($photoFolder);

        $sources = array_combine($sources, $targets);
        foreach($sources AS $source => $target){
            if(!move_uploaded_file($source, $photoFolder.$target)){
                trigger_error('Could not upload file', E_USER_WARNING);
                $database->rollback();
                return;
            }
        }
        trigger_error('The files have been uploaded successfully', E_USER_NOTICE);
        $database->commit();
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

        $userID         = $this->container->get('login')->getUserID();
        $photoFolder    = $this->container->get('aggregateConfig')->getConfig('photo', 'photoAssets');
        if(empty($userID)){
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



        $database   = $this->container->get('database');

        $query      = $database->getSelectSql($fields, $table, $params);

        if(false === $data = $database->query($query, $data, $types)){
            // user does not exist in the database
            if($database->getDbError())
                trigger_error($database->getDbError(), E_USER_WARNING);
            return array();
        }
        return $data;
    }

    public function insertPhotoData(string $photoPath, int $userID) : bool
    {

        $query      = "INSERT INTO `photo` (`photoPath`, `photoUser`) VALUES (?, ?)";
        $data       = Array($photoPath, $userID);
        $types      = Array('s', 'i');
        $database   = $this->getContainer()->get('database');
        if(false === $userID = $database->query($query, $data, $types)){
            trigger_error($database->getDbError(), E_USER_WARNING);
            return false;
        }

        return true;
    }

    public function drawThumbnail() : string
    {
        $data       = $this->getphotoData(4);
        $data       = array_merge($data, array_fill(0, abs(count($data)-4), []));
        $url        = $this->container->get('aggregateConfig')->getConfig('photo', 'url');

        $content    = Array('data' => $data, 'url' => $url);
        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__) . '/template/thumbnail.php', $content);
    }


    public function drawPage(): string
    {
        $data       = $this->getphotoData(6);
        $data       = array_merge($data, array_fill(0, abs(count($data)-6), []));
        $url        = $this->container->get('aggregateConfig')->getConfig('photo', 'url');

        $content    = Array('data' => $data, 'url' => $url);
        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__) . '/template/photo.php', $content);
    }
}
