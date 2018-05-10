<?php

declare(strict_types=1);

namespace application\task;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class taskFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {
        return new task($container->get('template'), $container->get('database'), $container->get('login'), array());
    }
}
