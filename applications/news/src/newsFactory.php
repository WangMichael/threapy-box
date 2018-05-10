<?php

declare(strict_types=1);

namespace application\news;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class newsFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        $config = $container->get('aggregateConfig')->getConfig('news');
        return new news($container->get('template'), $config);
    }
}
