<?php

declare(strict_types=1);

namespace application\photo;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class photoFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        return new photo($container);
    }
}
