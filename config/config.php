<?php

declare(strict_types=1);


return call_user_func(function(){
    $config = Array(

        'aggregateConfig' => Array(

            'httpDocs' => dirname(__DIR__).'/public',

            'login' => Array(
                'button' => '/assets/Login_button.png',
                'url'    => '/'
            ),
            'register' => Array(
                'button'    => '/assets/Register_button.png',
                'url'       => '/register/',
                'profile'   => '/profile/'
            ),

            'weather' => Array(
                'apiUrl'    => 'http://api.openweathermap.org/data/2.5/weather',
                'city'      => 'manchester',
                'token'     => 'd0a10211ea3d36b0a6423a104782130e',
                'cloud'     => '/assets/Clouds_icon.png',
                'rain'      => '/assets/Rain_icon.png',
                'sun'       => '/assets/Sun_icon.png'
            ),

            'clothes'=> Array(
                'apiUrl'    => 'https://therapy-box.co.uk/hackathon/clothing-api.php'
            ),

            'news'  => Array(
                'rssUrl'    => 'http://feeds.bbci.co.uk/news/rss.xml'
            ),

            'sport' => Array(
                'apiUrl'    => 'http://www.football-data.co.uk/mmz4281/1718/I1.csv',
            ),

            'development' => true,

            'dashboard' => Array(
            ),

            'photo'     => Array(
                'url'       => '/photo/',
                'photoAssets' => '/assets/photo/'
            ),

            'database'  => Array(
                'host'      => '127.0.0.1',
                'database'  => 'threapybox',
                'username'  => 'michael',
                'password'  => 'abc123',
            ),
        ),

        'container' => Array(

            'alias'     => Array(
                'aggregateConfig'   => framework\aggregateConfig\aggregateConfig::class,
                'template'          => framework\template\template::class,
                'database'          => framework\database\db::class,
                'image'             => framework\image\image::class,
                'login'             => application\login\loginController::class,
                'weather'           => application\weather\weather::class,
                'news'              => application\news\news::class,
                'clothes'           => application\clothes\clothes::class,
                'sport'             => application\sport\sport::class,
                'task'              => application\task\task::class,
                'photo'              => application\photo\photo::class,
            ),
            'factory'   => Array(
                framework\aggregateConfig\aggregateConfig::class => framework\aggregateConfig\aggregateConfigFactory::class,
                framework\template\template::class => framework\template\templateFactory::class,
                framework\database\db::class    => framework\database\databaseFactory::class,
                framework\image\image::class    => framework\image\imageFactory::class,
                application\dashboard\dashboardController::class => application\dashboard\dashboardFactory::class,
                application\login\loginController::class => application\login\loginFactory::class,
                application\news\news::class => application\news\newsFactory::class,
                application\sport\sport::class => application\sport\sportFactory::class,
                application\task\task::class => application\task\taskFactory::class,
                application\clothes\clothes::class => application\clothes\clothesFactory::class,
                application\photo\photo::class => application\photo\photoFactory::class,
                application\weather\weather::class => application\weather\weatherFactory::class
            )
        )
    );

    return $config;

});


