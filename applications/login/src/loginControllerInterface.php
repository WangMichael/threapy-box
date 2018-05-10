<?php

declare(strict_types=1);

namespace application\login;

use application\mvc\controllerInterface;

interface loginControllerInterface extends controllerInterface
{

    public function drawLogin();

    public function drawRegister();

    public function isAuthorised();

    public function checkLogin();

    public function destroy();

    public function checkRegister();

    public function getUserName() : string;

    public function getUserID() : int;

}
