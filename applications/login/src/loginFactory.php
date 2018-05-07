<?php

declare(strict_types=1);

namespace application\login;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class loginFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        $model  = new loginModel($container);
        $view   = new loginView($model);

        return new loginController($model, $view);
    }
}
