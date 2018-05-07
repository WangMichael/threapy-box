<?php

declare(strict_types=1);

namespace application\sport;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class sportFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        return new sport($container);
    }
}
