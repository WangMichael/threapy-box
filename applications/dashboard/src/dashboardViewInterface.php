<?php

declare(strict_types=1);

namespace application\dashboard;

use application\mvc\viewInterface;

interface dashboardViewInterface extends viewInterface
{

    public function drawCss() : string;

    public function drawJavascript() : string;

    public function drawPage();
}
