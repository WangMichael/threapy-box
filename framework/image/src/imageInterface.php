<?php

declare(strict_types=1);

namespace framework\image;

interface imageInterface
{

    public function isImage(string $filePath) : bool;

    public function getExtension(string $ext) : string;

    public function getRandomFileName(string $extension) : String;

    public function getMiMEType(string $filePath) : String;

}