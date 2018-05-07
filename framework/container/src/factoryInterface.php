<?php

declare(strict_types=1);

namespace framework\container;

interface factoryInterface
{

    public function __invoke(containerInterface $container, String $requestedName);
}