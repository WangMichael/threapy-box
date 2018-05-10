<?php

declare(strict_types=1);

namespace application\clothes;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class clothesFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        $config = $container->get('aggregateConfig')->getConfig('clothes');
        return new clothes($container->get('template'), $container->get('login'), $config);
    }
}
