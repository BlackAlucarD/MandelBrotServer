<?php

    // Routes
    $app->post('/', \App\Controllers\MandelBrotController::class . ':mandelBrotAction');
    $app->post('/multi', \App\Controllers\MandelBrotController::class . ':mandelBrotMultiAction');


