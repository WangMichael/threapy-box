<?php

declare(strict_types=1);

namespace application\weather;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class weatherFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {
        $config = $container->get('aggregateConfig')->getConfig('weather');
        return new weather($container->get('template'), $config);
    }
}
