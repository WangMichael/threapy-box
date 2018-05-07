<?php

declare(strict_types=1);

namespace application\weather;

use framework\container\containerInterface;

class weather implements weatherInterface
{

    private $container;

    public function __construct(containerInterface $container)
    {
        $this->container = $container;
    }

    public function getWeatherData(): array
    {
        $weatherConfig = $this->container->get('aggregateConfig')->getConfig('weather');

        if(!isset($weatherConfig['apiUrl'])){
            trigger_error('The weather API does not exist', E_USER_WARNING);
            return array();
        }

        if(!isset($weatherConfig['city'])){
            trigger_error('The weather City does not exist', E_USER_WARNING);
            return array();
        }

        if(!isset($weatherConfig['token'])){
            trigger_error('The weather API token does not exist', E_USER_WARNING);
            return array();
        }

        $url = $weatherConfig['apiUrl'].'?'.'q='.$weatherConfig['city'].'&appid='.$weatherConfig['token'];
        $json = file_get_contents($url);
        if(!$json){
            trigger_error('The weather connection has failed', E_USER_WARNING);
            return array();
        }

        $json           = json_decode($json, true);
        $data           = [];
        $data['temp']   = round($json['main']['temp'] - 273.15, 1);
        $data['city']   = $json['name'];
        $data['main']   = current($json['weather'])['main'];

        return $data;


    }

    public function drawThumbnail() : string
    {
        $data = $this->getWeatherData();

        $weatherConfig = $this->container->get('aggregateConfig')->getConfig('weather');

        $image = strtolower($data['main']);
        if(strpos($image, 'cloud') !== false)
            $image  = $weatherConfig['cloud'];
        elseif(strpos($image, 'sun') !== false)
            $image  = $weatherConfig['sun'];
        else
            $image  = $weatherConfig['rain'];


        $content    = Array('image' => $image, 'city' => $data['city'], 'degree' => $data['temp']);

        $template   = $this->container->get('template');

        return $template->render(dirname(__DIR__).'/template/weather.php', $content);

    }


}
