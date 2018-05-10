<?php

declare(strict_types=1);

namespace application\login;

use application\mvc\modelInterface;

interface loginModelInterface extends modelInterface
{

    public function checkLogin() : void;


    public function checkCSRF(string $identifier, string $token) : bool;


    public function checkSignUp() : void;


    public function destroy() : void;


    public function CSRFToken(string $identifier) : String;


    public function isAuthorised() : bool;


    public function getPopulatedData() : array;


    public function getUserName() : String;


    public function getConfig() : array;

}
