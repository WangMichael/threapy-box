<?php

declare(strict_types=1);

namespace application\weather;

use framework\template\templateInterface;

class weather implements weatherInterface
{
    private $template;

    private $config;


    public function __construct(templateInterface $template, array $config)
    {
        $this->template     = $template;
        $this->config       = $config;
    }

    public function getWeatherData(): array
    {

        if(!isset($this->config['apiUrl'])){
            trigger_error('The weather API does not exist', E_USER_WARNING);
            return array();
        }

        if(!isset($this->config['city'])){
            trigger_error('The weather City does not exist', E_USER_WARNING);
            return array();
        }

        if(!isset($this->config['token'])){
            trigger_error('The weather API token does not exist', E_USER_WARNING);
            return array();
        }

        $url = $this->config['apiUrl'].'?'.'q='.$this->config['city'].'&appid='.$this->config['token'];
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

        $image = strtolower($data['main']);
        if(strpos($image, 'cloud') !== false)
            $image  = $this->config['cloud'];
        elseif(strpos($image, 'sun') !== false)
            $image  = $this->config['sun'];
        else
            $image  = $this->config['rain'];


        $content    = Array('image' => $image, 'city' => $data['city'], 'degree' => $data['temp']);

        return $this->template->render(dirname(__DIR__).'/template/weather.php', $content);

    }


}
