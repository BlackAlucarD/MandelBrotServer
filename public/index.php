<?php
    // to get autoclass_map etc
    require __DIR__ . '/../vendor/autoload.php';

    // get the settings for slim
    $settings = require __DIR__ . '/../app/settings.php';
    $app      = new \Slim\App($settings);

    // set the dependencies like controller, view and logger
    require __DIR__ . '/../app/dependencies.php';

    // set the routes
    require __DIR__ . '/../app/routes.php';

    $app->run();
