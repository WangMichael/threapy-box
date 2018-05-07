<?php

declare(strict_types=1);

namespace framework\image;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class imageFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        return new image();
    }
}
