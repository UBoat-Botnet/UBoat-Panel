<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

return [
    'base_url' => 'http://localhost/', // Base URL including trailing slash (e.g. http://localhost/)
    'default_controller' => 'main', // Default controller to load
    'encoding' => 'utf-8',
    'error_controller' => 'errors', // Controller used for errors (e.g. 404, 500 etc)
    'error_type' => [
        //default 404
        '403' => 'error403',
        '404' => 'error404',
    ],
    'db' => [
        'username' => 'uboat',
        'password' => 'uboat',
        'host' => 'mysql',
        'dbname' => 'uboat',
    ],
    //screenshot settings
    'screenshots' => [
        'enabled' => 'true',
        'external_address' => 'localhost', // 'domain_goes_here', localhost for local testing only
        'port' => '25019',
    ],
    //capcha settings
    'capcha' => [
        'random_color' => 'true',
        'char_pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'angle' => ['-30', '30'],
        'fonts' => ['Roboto-Medium.ttf', 'Roboto-Italic.ttf'],
        'font_size' => ['10.0', '11.0'],
    ],
    //key for logs encryption
    'key' => 'root',
    //this can be turned off,
    'display_login_error' => 'true',

    // Include assets config
    'assets' => 'assets.php',
];
