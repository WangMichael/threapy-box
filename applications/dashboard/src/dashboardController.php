<?php

declare(strict_types=1);

namespace application\dashboard;

class dashboardController implements dashboardControllerInterface
{
    private $model;

    private $view;

    public function __construct(dashboardModelInterface $model, dashboardViewInterface $view)
    {
        $this->model    = $model;
        $this->view     = $view;

    }

    public function getView()
    {
        return $this->view;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function drawPage()
    {
        return $this->view->drawPage();
    }


}
