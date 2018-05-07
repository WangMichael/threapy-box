<?php

declare(strict_types=1);

namespace application\clothes;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class clothesFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {
        return new clothes($container);
    }
}