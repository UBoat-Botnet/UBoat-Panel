<?php
/*
 * Goat v0.0.1
 */

session_start();
// Defines
define('ROOT_DIR', realpath(dirname(__FILE__) . '/../'));
define('APP_DIR', ROOT_DIR .'/src');
define('WEB_BASE', sprintf("%s",pathinfo($_SERVER['PHP_SELF'])['dirname']));
define('WEB_DIR' , WEB_BASE . '/www/');

spl_autoload_register(function ($class) {
    include APP_DIR . '/' . $class . '.php';
});

$config = require(APP_DIR .'/config/config.php');

// Define base URL
define('BASE_URL', $config['base_url']);

return new goat($config);
