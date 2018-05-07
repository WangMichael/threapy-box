<?php

declare(strict_types=1);

namespace application\weather;

interface weatherInterface
{

    public function getWeatherData() : array;
    public function drawThumbnail() : string;

}
