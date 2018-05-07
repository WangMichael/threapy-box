<?php

declare(strict_types=1);

namespace application\clothes;

interface clothesInterface
{

    public function getClothesData() : array;
    public function drawThumbnail() : string;

}
