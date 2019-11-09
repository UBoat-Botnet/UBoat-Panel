<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Model
{
    private $connection;

    public function __construct()
    {
        $this->connection = new \PDO('mysql:host='.goat::$app->config['db']['host'].';dbname='.goat::$app->config['db']['dbname'], goat::$app->config['db']['username'], goat::$app->config['db']['password']); //mysql_pconnect(goat::$config['db_host'], goat::$app->config['db_username'], goat::$app->config['db_password']) or die('MySQL Error: '. mysql_error());
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function to_bool($val)
    {
        return (bool) $val;
    }

    public function to_date($val)
    {
        return date('Y-m-d', $val);
    }

    public function to_time($val)
    {
        return date('H:i:s', $val);
    }

    public function to_datetime($val)
    {
        return date('Y-m-d H:i:s', $val);
    }

    public function query($qry, $params = []) //$binded, &$result = null, $fetch = true
    {
        try {
            (isset($params['binded']) ?: $params['binded'] = []);
            $statement = $this->connection->prepare($qry);
            if (null != isset($params['results'])) {
                $params['results'] = $statement->execute($params['binded']);
            } else {
                $statement->execute($params['binded']);
            }
            if (isset($params['fetch'])) {
                if ('all' == $params['fetch']) {
                    return $statement->fetchAll();
                }
            }

            return $statement->fetch();

            return 'success';
        } catch (PDOException $Exception) {
            die(var_dump(new MyDatabaseException($Exception->getMessage(), $Exception->getCode())));
        }
    }
}
