<?php

declare(strict_types=1);

namespace application\dashboard;

use application\mvc\controllerInterface;

interface dashboardControllerInterface extends controllerInterface
{

    public function drawPage();

}
