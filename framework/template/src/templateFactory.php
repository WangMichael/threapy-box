<?php

declare(strict_types=1);

namespace framework\template;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class templateFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {

        return new template();
    }
}
