<?php

declare(strict_types=1);

namespace application\dashboard;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class dashboardFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        $model  = new dashboardModel($container);
        $view   = new dashboardView($model);

        return new dashboardController($model, $view);
    }
}
