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


    public function route(string $path) : string
    {
        $login  = $this->container->get('login');

        // log out
        if($path == '/log_out/'){
            $login->destroy();
            header('Location: /', true,301);
            exit();
        }

        // authenticate and redirect the page
        if(!$login->isAuthorised()){


            if($path === '/'){
                $login->checkLogin();
                return $login->drawLogin();
            }

            if($path === '/register/'){
                $login->checkRegister();
                return $login->drawRegister();
            };

            $path = '/'.'?'.urlencode('target='.$path);
            header('Location: '.$path.'/', true,301);
            exit();
        }

        // direct to the dashboard if the page is sign in or sign up
        if(in_array($path, array('/', '/register/'))){
            header('Location: /dashboard', true,301);
            exit();
        }

        // rendering photos
        if($path === '/photo/'){
            $photos = $this->container->get('photo');
            $photos->processImages();
            return $photos->drawPage();
        }

        // rendering task
        if($path === '/task/'){
            $task  = $this->container->get('task');
            $task->processTask();
            return $task->drawPage();
        }


        // rendering sports
        if($path === '/sport/'){
            $sports  = $this->container->get('sport');
            return $sports->drawPage();
        }

        // rendering the news
        if($path === '/news/'){
            $news   = $this->container->get('news');
            return $news->drawPage();
        }

        if($path === '/dashboard/'){

            $thumbnails             = [];
            $thumbnails['weather']  = $this->container->get('weather')->drawThumbnail();
            $thumbnails['news']     = $this->container->get('news')->drawThumbnail();
            $thumbnails['sport']    = $this->container->get('sport')->drawThumbnail();
            $thumbnails['photo']    = $this->container->get('photo')->drawThumbnail();
            $thumbnails['clothes']  = $this->container->get('clothes')->drawThumbnail();
            $thumbnails['task']     = $this->container->get('task')->drawThumbnail();

            $template = $this->container->get('template');

            return $template->render(dirname(__DIR__).'/templates/thumbnail.php', array('thumbnails' => $thumbnails));
        }

        return '';

    }
}
