<?php

declare(strict_types=1);

namespace application\news;

use framework\container\container;

class news implements newsInterface
{


    private $container;

    public function __construct(container $container)
    {
        $this->container = $container;
    }

    public function getNewsData(): array
    {
        $url  = $this->container->get('aggregateConfig')->getConfig('news', 'rssUrl');


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

        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__) . '/template/thumbnail.php', array('news'=>$data));
    }


    public function drawPage(): string
    {
        $data = $this->getNewsData();

        $content = Array('title' => $data['title'], 'url' => $data['url'],
                        'width' => $data['width'], 'height' => $data['height'],
                        'description' => $data['description']);

        $template   = $this->container->get('template');
        return $template->render(dirname(__DIR__) . '/template/news.php', $content);

    }
}
