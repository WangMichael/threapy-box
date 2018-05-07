<?php

declare(strict_types=1);

namespace framework\aggregateConfig;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class aggregateConfigFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        return new aggregateConfig();
    }
}
