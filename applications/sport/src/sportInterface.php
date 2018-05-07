<?php

declare(strict_types=1);

namespace application\sport;


interface sportInterface
{

    public function drawPage() : string;

    public function drawThumbnail() : string;
}
