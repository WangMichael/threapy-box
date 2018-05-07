<?php

declare(strict_types=1);

namespace application\weather;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class weatherFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {
        return new weather($container);
    }
}
