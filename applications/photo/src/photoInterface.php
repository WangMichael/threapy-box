<?php

declare(strict_types=1);

namespace application\photo;

interface photoInterface
{
    public function getPhotoData(int $limit): array;

    public function drawThumbnail() : string;

    public function drawPage() : string;

    public function processImages() : void;
}
