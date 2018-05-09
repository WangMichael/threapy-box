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

        $content['content'] = $this->model->route($path);

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
