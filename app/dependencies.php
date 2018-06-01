<?php
    // DIC configuration
    $container = $app->getContainer();

    // View
    $container['view'] = new \Slim\Views\PhpRenderer('/../app/templates/');

    // MonoLog
    $container['logger'] = function ($c) {
        $logger       = new \Monolog\Logger('my_logger');
        $file_handler = new \Monolog\Handler\StreamHandler('/logs/app.logs');
        $logger->pushHandler($file_handler);
        return $logger;
    };

    // Controllers
    $container['MandelBrotController'] = function ($c) {
        return new App\Controllers\MandelBrotController($c);
    };