<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Controller
{
    private $_behaviors = [];

    public function __construct()
    {
        $this->beforeRequest();
    }

    public function behaviors()
    {
        return [];
    }

    public function loadModel($name)
    {
        require APP_DIR.'/models/'.strtolower($name).'.php';

        $model = new $name();

        return $model;
    }

    public function loadView($name)
    {
        $view = new View($name);

        return $view;
    }

    public function loadPlugin($name)
    {
        require APP_DIR.'/plugins/'.strtolower($name).'.php';
    }

    public function loadHelper($name)
    {
        require APP_DIR.'/helpers/'.strtolower($name).'.php';
        $helper = new $name();
        return $helper;
    }

    public function redirect($loc)
    {
        header('Location: '.goat::$app->config['base_url'].$loc);
        die();
    }

    public function route()
    {
        return $_SERVER['REQUEST_URI'];
    }

    //check behaviors
    private function beforeRequest()
    {
        //this is overriden, then check if data is set.
        $this->_behaviors = $this->behaviors();
        //requires logging
        if (isset($this->_behaviors['access'])) {
            //all auth users, no matters what role.
            if (isset($this->_behaviors['access']['rules']['isLogged']) && 'true' == $this->_behaviors['access']['rules']['isLogged']) {
                //load the helper , might pass error as param
                $helper = $this->loadHelper('AuthVerify');
                $resp = $helper->verify();

                if (isset($resp['Error'])) {
                    (isset(goat::$app->config['display_login_error']) && 'true' == goat::$app->config['display_login_error']) ? goat::$app->setFlash('_login_error', $resp) : '';
                    $template = $this->redirect(goat::$app->config['error_controller'].'/'.goat::$app->config['error_type']['403']); //this should be set
                    $template->render();
                }

                //nothing to do
            }
        }
    }
}
