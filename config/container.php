<?php

declare(strict_types=1);

use framework\container\container;


return call_user_func(function(){

    // Load configuration
    $config = require __DIR__ . '/config.php';

    // error report
    if(isset($config['aggregateConfig']['development'])){
        $dev = (string)$config['aggregateConfig']['development'];
        error_reporting(E_ALL); //
        ini_set('display_errors', $dev);
        ini_set('display_startup_errors', $dev);
    }

    $container          = new container(($config['container']));
    // set Error Handler
    $container->get('template')->setErrorHandler();

    $aggregateConfig    = $container->get('aggregateConfig');

    $aggregateConfig->setConfig($config['aggregateConfig']);

    return $container;

});
