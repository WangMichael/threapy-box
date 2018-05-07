<?php

declare(strict_types=1);

namespace application\task;

interface taskInterface
{
    public function getTaskData(int $limit): array;

    public function drawThumbnail() : string;

    public function drawPage() : string;
}
