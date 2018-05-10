<?php

declare(strict_types=1);

namespace application\login;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class loginFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {


        $aggregateConfig    = $container->get('aggregateConfig');
        $config             = [];
        $config['register'] = $aggregateConfig->getConfig('register');
        $config['login']    = $aggregateConfig->getConfig('login');
        $config['httpDocs'] = $aggregateConfig->getConfig('httpDocs');;

        $model  = new loginModel($container->get('database'), $container->get('image'), $config);
        $view   = new loginView($model, $container->get('template'));

        return new loginController($model, $view);
    }
}
