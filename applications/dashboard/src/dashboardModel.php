<?php

declare(strict_types=1);

namespace application\dashboard;

use framework\container\containerInterface;

class dashboardModel implements dashboardModelInterface
{

    private $css    = [];

    private $js     = [];

    private $container;

    public function __construct(containerInterface $container)
    {
        $this->container    = $container;
    }

    public function includeCss(string $file)
    {
        if (!in_array($file, $this->css))
            $this->css[] = $file;

        return $this;
    }

    public function includeJs(string $file)
    {
        if(!in_array($file, $this->js))
            $this->js[] = $file;

        return $this;
    }

    public function getContainer(): containerInterface
    {
        return $this->container;
    }

    public function getCss() : array
    {
        return $this->css;
    }

    public function getJavaScript() : array
    {
        return $this->js;
    }


    public function getScriptName(string $requestUri)
    {
        $requestUri = str_replace('\\', '/', $requestUri);

        if (($path = parse_url($requestUri, PHP_URL_PATH)) === false)
            trigger_error("Could not process script, The requested URL is invalid", E_USER_ERROR);

        // when the address is a folder and does not end with a slash, redirect to with a slash
        $end = strrchr($path, '/');
        if ($end != '/' && strpos($end, '.') === false) {
            header('Location: '.$path.'/', true,301);
            exit();
        }
        return $path;

    }
}
