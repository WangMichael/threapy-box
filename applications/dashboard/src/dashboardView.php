<?php

declare(strict_types=1);

namespace application\dashboard;


class dashboardView implements dashboardViewInterface
{
    private $model;

    public function __construct(dashboardModelInterface $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }


    public function drawCss() : string
    {
        $html = '';
        foreach ($this->model->getCss() AS $item) {
            $html .= '<link rel="stylesheet" href="'.htmlspecialchars($item).'" />';
        }
        return $html;
    }

    public function drawJavascript() : string
    {
        $html = '';
        foreach ($this->model->getJavaScript() AS $item) {
            $html .= '<script src="'.htmlspecialchars($item).'"></script>';
        }
        return $html;
    }


    private function drawContent(string $path) : string
    {
        $login  = $this->model->getContainer()->get('login');

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
            $photos = $this->model->getContainer()->get('photo');
            $photos->processImages();
            return $photos->drawPage();
        }

        // rendering task
        if($path === '/task/'){
            $task  = $this->model->getContainer()->get('task');
            $task->processTask();
            return $task->drawPage();
        }


        // rendering sports
        if($path === '/sport/'){
            $sports  = $this->model->getContainer()->get('sport');
            return $sports->drawPage();
        }

        // rendering the news
        if($path === '/news/'){
            $news   = $this->model->getContainer()->get('news');
            return $news->drawPage();
        }

        if($path === '/dashboard/'){

            $thumbnails             = [];
            $thumbnails['weather']  = $this->model->getContainer()->get('weather')->drawThumbnail();
            $thumbnails['news']     = $this->model->getContainer()->get('news')->drawThumbnail();
            $thumbnails['sport']    = $this->model->getContainer()->get('sport')->drawThumbnail();
            $thumbnails['photo']    = $this->model->getContainer()->get('photo')->drawThumbnail();
            $thumbnails['clothes']  = $this->model->getContainer()->get('clothes')->drawThumbnail();
            $thumbnails['task']     = $this->model->getContainer()->get('task')->drawThumbnail();

            $template = $this->model->getContainer()->get('template');

            return $template->render(dirname(__DIR__).'/templates/thumbnail.php', array('thumbnails' => $thumbnails));
        }

        return '';

    }


    public function drawPage()
    {
        $path = $this->model->getScriptName($_SERVER['REQUEST_URI']);

        $this->model->includeCss('/stylesheets/bootstrap.css');
        $this->model->includeJs('/javascript/bootstrap.js');

        $container  = $this->model->getContainer();
        $template   = $container->get('template');


        $content = Array(
            'css'       => $this->drawCss(),
            'js'        => $this->drawJavascript(),
            'content'   => '',
        );

        $routes = array(
            '/', '/dashboard/', '/register/', '/task/', '/log_out/','/news/', '/photo/', '/sport/'
        );
        if(!in_array($path, $routes)){
            $content['content'] = "<div>Page is not found</div>";
            header('HTTP/1.1 404 Not Found');
            $html       = $template->render(dirname(__DIR__).'/templates/dashboard.php', $content);
            exit($html);
        }

        $content['content'] = $this->drawContent($path);

        // insert message after the first H1 tag
        $msg = $template->drawErrorMsg();
        if (strpos($content['content'], '<h1') !== false && ($pos = strpos($content['content'], '</h1>')) !== false)
            $content['content'] = substr_replace($content['content'], $msg, $pos + 5, 0);
        else
            $content['content'] = $msg.$content['content'];

        $html               = $template->render(dirname(__DIR__).'/templates/dashboard.php', $content);


        exit($html);
    }
}
