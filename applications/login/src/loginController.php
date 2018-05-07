<?php

declare(strict_types=1);

namespace application\login;

class loginController implements loginControllerInterface
{
    private $model;

    private $view;

    public function __construct(loginModelInterface $model, loginViewInterface $view)
    {
        $this->model    = $model;
        $this->view     = $view;
    }

    public function drawLogin()
    {
        return $this->view->drawLogin();
    }

    public function drawRegister()
    {
        return $this->view->drawRegister();
    }


    public function checkLogin()
    {
        $this->model->checkLogin();
    }

    public function destroy()
    {
        $this->model->destroy();
    }


    public function checkRegister()
    {
        $this->model->checkSignUp();
    }

    public function isAuthorised() : bool
    {
        return $this->model->isAuthorised();
    }

    public function getView()
    {
        return $this->view;
    }

    public function getModel()
    {
        return $this->model;
    }


    public function getUserName() : string
    {
        return $this->model->getUserName();
    }




    public function getUserID() : int
    {
        return $this->model->getUserID();
    }


}
