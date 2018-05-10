<?php

declare(strict_types=1);

namespace application\news;

use framework\template\templateInterface;

class news implements newsInterface
{


    private $template;

    private $config;

    public function __construct(templateInterface $template, array  $config)
    {
        $this->template = $template;
        $this->config   = $config;
    }

    public function getNewsData(): array
    {

        $url        = $this->config['rssUrl'];
        $xml        = simplexml_load_file($url);
        $thumbnails = $xml->xpath ('channel/item/media:thumbnail');
        $thumbnail  = json_decode(json_encode($thumbnails[0]), true);
        $thumbnail  = $thumbnail['@attributes'];
        $xml   = $xml->channel->item[0];
        $data['title']          = (string)$xml->title;
        $data['description']    = (string)$xml->description;


        return array_merge($data, $thumbnail);

    }

    public function drawThumbnail() : string
    {
        $data = $this->getNewsData();

        return $this-> template->render(dirname(__DIR__) . '/template/thumbnail.php', array('news'=>$data));
    }


    public function drawPage(): string
    {
        $data = $this->getNewsData();

        $content = Array('title' => $data['title'], 'url' => $data['url'],
                        'width' => $data['width'], 'height' => $data['height'],
                        'description' => $data['description']);

        return $this->template->render(dirname(__DIR__) . '/template/news.php', $content);

    }
}
