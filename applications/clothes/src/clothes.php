<?php

declare(strict_types=1);

namespace application\clothes;

use application\login\loginControllerInterface;
use framework\template\templateInterface;

class clothes implements clothesInterface
{

    private $config;

    private $login;

    private $template;

    public function __construct(templateInterface $template, loginControllerInterface $login, array $config)
    {

        $this->template = $template;
        $this->login    = $login;
        $this->config   = $config;
    }

    public function getClothesData(): array
    {

        if(!isset($this->config['apiUrl'])){
            trigger_error('The clothes API does not exist', E_USER_WARNING);
            return array();
        }

        if(!$username = $this->login->getUserName()){
            trigger_error('The username does not exist', E_USER_WARNING);
            return array();
        }

        $url = $this->config['apiUrl'].'?'.'username='.$username;
        $json = file_get_contents($url);
        if(!$json){
            trigger_error('The clothes connection has failed', E_USER_WARNING);
            return array();
        }
        $data           = json_decode($json, true);
        $data           = $data['payload'];

        foreach($data AS $key => $value){
            if(!isset($data[$value['clothe']]))
                $data[$value['clothe']] =  1;
            else
                $data[$value['clothe']] += 1;
            unset($data[$key]);
        }

        return $data;


    }

    public function drawThumbnail() : string
    {
        $data       = $this->getclothesData();
        $content    = Array('data' => $data);
        return $this->template->render(dirname(__DIR__).'/template/clothes.php', $content);
    }


}
