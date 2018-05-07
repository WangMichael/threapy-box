<?php

declare(strict_types=1);

namespace framework\container;

interface containerInterface{

    public function get(String $service);

    public function has(String $service) : boolean;
}