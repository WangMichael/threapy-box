<?php

declare(strict_types=1);

namespace application\login;


class loginView implements loginViewInterface
{
    private $model;


    public function __construct(loginModelInterface $model)
    {
        $this->model = $model;
    }


    public function drawLogin() : String
    {
        $populated              = $this->model->getPopulatedData();
        $username               = $populated['username'] ?? '';
        $loginCSRF              = $this->model->CSRFToken('loginCSRF');
        $aggregateConfig        = $this->model->getContainer()->get('aggregateConfig');
        $button                 = $aggregateConfig->getConfig('login', 'button');
        $url                    = $aggregateConfig->getConfig('login', 'url');
        $content                = Array('username'  => $username,   'loginCSRF' => $loginCSRF,
                                        'url'       => $url,        'button'    => $button);
        $template               = $this->model->getContainer()->get('template');
        return $template->render(dirname(__DIR__).'/template/login.php', $content);

    }


    public function drawRegister() : String
    {
        $populated              = $this->model->getPopulatedData();
        $username               = $populated['username'] ?? '';
        $email                  = $populated['email'] ?? '';
        $registerCSRF           = $this->model->CSRFToken('registerCSRF');
        $aggregateConfig        = $this->model->getContainer()->get('aggregateConfig');
        $button                 = $aggregateConfig->getConfig('register', 'button');
        $url                    = $aggregateConfig->getConfig('register', 'url');
        $content                = Array('username'=>$username, 'email'=> $email, 'url' => $url,
                                        'registerCSRF' => $registerCSRF, 'button' => $button);
        $template               = $this->model->getContainer()->get('template');
        return $template->render(dirname(__DIR__).'/template/register.php', $content);
    }


    public function getModel()
    {
        return $this->model;
    }
}
