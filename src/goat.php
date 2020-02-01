<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class goat
{
    public static $app;
    public $config;

    protected $matched_controller;
    protected $matched_action;

    public function __construct($config)
    {
        // Set our defaults
        $this::$app = $this;
        $this->config = $config;
        $controller = $config['default_controller'];
        $action = 'index';
        $url = '';

        // Get request url and script url
        $request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
        $script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';

        // Get our url path and trim the / of the left and the right
        if ($request_url != $script_url && $request_url.'.php' != $script_url) {
            $url = trim(preg_replace('/'.str_replace('/', '\/', str_replace('index.php', '', $script_url)).'/', '', $request_url, 1), '/');
        }

        // Split the url into segments
        $segments = explode('/', $url);

        // Do our default checks
        if (isset($segments[0]) && '' != $segments[0]) {
            if (strstr($segments[0], '.')) {
                $_t = explode('.', $segments[0]);
                $controller = $_t[0];
            } else {
                $controller = $segments[0];
            }
        }

        if (isset($segments[1]) && '' != $segments[1]) {
            $action = $segments[1];
        }
        if (strstr($segments[0], '?') && strstr($segments[0], '=')) {
            $controller = explode('?', $segments[0])[0];
        }

        $path = APP_DIR.'/controllers/'.$controller.'.php';

        if (file_exists($path)) {
            require_once $path;
        } else {
            $controller = $this->config['error_controller'];
            require_once APP_DIR.'/controllers/'.$controller.'.php';
        }

        // Check the action exists
        if (! method_exists($controller, $action)) {
            $controller = $this->config['error_controller'];
            require_once APP_DIR.'/controllers/'.$controller.'.php';
            $action = 'index';
        }

        // Create object and call method
        $obj = new $controller();

        $this->matched_controller = $controller;
        $this->matched_action = $action;

        return call_user_func_array([$obj, $action], array_slice($segments, 2));
    }

    public function cleanPage()
    {
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        return true;
    }

    //flashes session values can be set/seen once. now can support views count
    public function setFlash($key, $mixed, $views = 0)
    {
        $_SESSION['_flash'][$key] = $mixed;
        $_SESSION['_flash'][$key.'_views'] = $views;
    }

    // allow to ignore the delete behaviour
    public function getFlash($key, $ignore = false)
    {
        if (! isset($_SESSION['_flash'][$key])) {
            return null;
        }
        $flash = $_SESSION['_flash'][$key];
        if (! $ignore) {
            $_views = $_SESSION['_flash'][$key.'_views'];
            if (0 == $_views) {
                unset($_SESSION['_flash'][$key.'_views']);
                unset($_SESSION['_flash'][$key]);
            } else {
                $_views = (int) $_views - 1;
                $_SESSION['_flash'][$key.'_views'] = $_views;
            }
        }

        return $flash;
    }

    //get rid of all pending flashes
    public function clearFlash()
    {
        unset($_SESSION['_flash']);

        return true;
    }

    public function registerJs($inlineJS)
    {
        if (! empty($inlineJS)) {
            echo '<script type="text/javascript">'.$inlineJS.'</script>';
        }
    }

    public function getMatchedController()
    {
        return $this->matched_controller;
    }

    public function getMatchedAction()
    {
        return $this->matched_action;
    }
}
