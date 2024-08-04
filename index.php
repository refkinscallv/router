<?php

    use RF\Router\Route;

    require "vendor/autoload.php";

    Route::get('/hello', function() {
        echo "Hello World";
    });

    Route::group(['prefix' => '/page'], function() {
        Route::get('/about', function() {
            echo "Page::about";
        });
    });

    Route::run();
