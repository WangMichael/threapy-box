<?php

declare(strict_types=1);

namespace framework\template;

interface templateInterface{

    public function render(String $templatePath, Array $containerVariable):String;

    public function drawErrorMsg() : string;


    public function setErrorHandler() : void;
}