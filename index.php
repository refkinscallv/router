<?php

    // Importing Library
    use RF\Router\Route;

    // Autoload dependencies
    require 'vendor/autoload.php';

    /**
     * 1. Register Routes from Separate Files
     * 
     * You can register routes from different files using the Route::register method.
     * 
     * Example file: example/route/other.php
     * 
     * Use:
     * Route::get('/elsewhere', function() {
     *     echo 'Routes elsewhere';
     * });
     */
    Route::register([
        'example/route/other'
    ]);

    /**
     * 2. Global Settings
     * 
     * Set up maintenance and 404 page handlers.
     */

    // Maintenance Page Handler
    Route::setMaintenance(function() {
        echo 'Maintenance Page';
    });

    // Alternatively, use a PHP file or class method
    // require 'example/page/maintenance.php';
    // Route::setMaintenance([RF\Page\Maintenance::class, 'index']);

    // 404 Page Handler
    Route::set404(function() {
        echo 'Page Not Found';
    });

    // Alternatively, use a PHP file or class method
    // require 'example/page/page404.php';
    // Route::set404([RF\Page\Page404::class, 'index']);

    /**
     * 3. Basic Route Usage
     * 
     * Define routes with different HTTP methods:
     * - Route::get
     * - Route::post
     * - Route::put
     * - Route::delete
     * - Route::patch
     * - Route::options
     */

    // Define a default GET route
    Route::get('', function() {
        echo 'Default Page';
    });

    // Alternatively, use a PHP file or class method
    // require 'example/page/default.php';
    // Route::get('', [RF\Page\DefaultPage::class, 'index']);

    /**
     * 4. Route Groups
     * 
     * Group routes under a common prefix.
     */
    Route::group(['prefix' => '/parent/path'], function() {
        Route::get('', function() {
            echo 'Default Page for /parent/path';
        });

        Route::get('/child', function() {
            echo 'Path /child page of /parent/path';
        });
    });

    // Run the router to handle incoming requests
    Route::run();
