<?php

declare(strict_types=1);

namespace application\login;


use framework\template\templateInterface;

class loginView implements loginViewInterface
{
    private $model;

    private $template;


    public function __construct(loginModelInterface $model, templateInterface $template)
    {
        $this->model        = $model;
        $this->template     = $template;
    }


    public function drawLogin() : String
    {
        $populated              = $this->model->getPopulatedData();
        $username               = $populated['username'] ?? '';
        $loginCSRF              = $this->model->CSRFToken('loginCSRF');
        $config                 = $this->model->getConfig();
        $button                 = $config['login']['button'];
        $url                    = $config['login']['url'];
        $content                = Array('username'  => $username,   'loginCSRF' => $loginCSRF,
                                        'url'       => $url,        'button'    => $button);
        return $this->template->render(dirname(__DIR__).'/template/login.php', $content);

    }


    public function drawRegister() : String
    {
        $populated              = $this->model->getPopulatedData();
        $username               = $populated['username'] ?? '';
        $email                  = $populated['email'] ?? '';
        $registerCSRF           = $this->model->CSRFToken('registerCSRF');
        $config                 = $this->model->getConfig();
        $button                 = $config['register']['button'];
        $url                    = $config['register']['url'];
        $content                = Array('username'=>$username, 'email'=> $email, 'url' => $url,
                                        'registerCSRF' => $registerCSRF, 'button' => $button);
        return $this->template->render(dirname(__DIR__).'/template/register.php', $content);
    }


    public function getModel()
    {
        return $this->model;
    }
}
