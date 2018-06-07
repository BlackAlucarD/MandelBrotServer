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

    // Give the system UNLIMITED TIME
    set_time_limit(0);
    ini_set('max_input_time', 0);
    // and UNLIMITED POWER (memory)
    ini_set('memory_limit', -1);

    try {
        $app->run();
    } catch (Exception $exception) {
        echo 'Error: ' . $exception;
        exit;
    }

