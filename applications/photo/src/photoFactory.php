<?php

declare(strict_types=1);

namespace application\photo;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class photoFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        $aggregateConfig    = $container->get('aggregateConfig');
        $config             = [];
        $config['httpDocs'] = $aggregateConfig->getConfig('httpDocs');
        $config['photo']    = $aggregateConfig->getConfig('photo');
        return new photo($container->get('template'), $container->get('database'),
                        $container->get('image'), $container->get('login'), $config);
    }
}
