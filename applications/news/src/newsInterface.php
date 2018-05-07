<?php

declare(strict_types=1);

namespace application\news;

interface newsInterface
{
    public function getNewsData(): array;

    public function drawThumbnail() : string;
}
