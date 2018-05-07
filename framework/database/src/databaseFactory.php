<?php

declare(strict_types=1);

namespace framework\database;

use framework\container\factoryInterface;
use framework\container\containerInterface;

class databaseFactory implements factoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestName)
    {
        $aggregateConfig = $container->get('aggregateConfig');
        $config          = $aggregateConfig->getConfig('database');

        if(empty($config['host']))
            trigger_error('The host is not specified', E_USER_ERROR);

        if(empty($config['database']))
            trigger_error('The database is not specified', E_USER_ERROR);

        if(empty($config['username']))
            trigger_error('The username is nto specified', E_USER_ERROR);

        if(empty($config['password']))
            trigger_error('The password is not specified', E_USER_ERROR);


        $connection = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

        if(!$connection)
            trigger_error('The database connection has failed', E_USER_ERROR);

        $database        = new db($connection);
        return $database;
    }
}
