<?php

declare(strict_types=1);

namespace application\login;

use application\mvc\viewInterface;

interface loginViewInterface extends viewInterface
{

    public function drawLogin();

    public function drawRegister();
}
