<?php

declare(strict_types=1);

namespace application\mvc;

use framework\container\containerInterface;

interface modelInterface
{
    public function getContainer() : containerInterface;
}
