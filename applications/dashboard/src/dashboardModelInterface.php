<?php

declare(strict_types=1);

namespace application\dashboard;

use application\mvc\modelInterface;

interface dashboardModelInterface extends modelInterface
{
    public function includeCss(string $file);

    public function includeJs(string $file);

    public function getCss() : array;

    public function getJavaScript() : array;

    public function getScriptName(string $requestUri);

    public function route(string $path) : string;
}
