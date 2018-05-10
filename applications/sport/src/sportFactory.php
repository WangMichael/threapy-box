<?php

declare(strict_types=1);

namespace application\sport;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class sportFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        $config = $container->get('aggregateConfig')->getConfig('sport');
        return new sport($container->get('template'), $config);
    }
}
