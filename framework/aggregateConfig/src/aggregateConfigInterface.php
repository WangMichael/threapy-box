<?php

declare(strict_types=1);

namespace framework\aggregateConfig;

interface aggregateConfigInterface
{

    public function setConfig(array $config, array $namespace = array(), bool $overwrite = false);

    public function getConfig();

}