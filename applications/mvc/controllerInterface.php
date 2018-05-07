<?php

declare(strict_types=1);

namespace application\mvc;

interface controllerInterface
{

    public function getModel();

    public function getView();
}
