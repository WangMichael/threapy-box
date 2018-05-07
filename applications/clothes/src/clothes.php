<?php

declare(strict_types=1);

namespace application\clothes;

use framework\container\containerInterface;

class clothes implements clothesInterface
{

    private $container;

    public function __construct(containerInterface $container)
    {
        $this->container = $container;
    }

    public function getClothesData(): array
    {
        $clothesConfig = $this->container->get('aggregateConfig')->getConfig('clothes');

        if(!isset($clothesConfig['apiUrl'])){
            trigger_error('The clothes API does not exist', E_USER_WARNING);
            return array();
        }

        $username     = $this->container->get('login')->getUserName();
        if(empty($username)){
            trigger_error('The username does not exist', E_USER_WARNING);
            return array();
        }

        $url = $clothesConfig['apiUrl'].'?'.'username='.$username;
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
        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__).'/template/clothes.php', $content);
    }


}
