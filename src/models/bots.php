<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class bots extends Model
{
    public function clearDeads()
    {
        $this->query('DELETE FROM `bots` WHERE `lastseen` <  now() - interval 7 day');

        return true;
    }

    public function getCountries()
    {
        return $this->query('SELECT `country_code` FROM `bots`', ['fetch' => 'all']);
    }

    public function getOS()
    {
        return $this->query('SELECT `os` FROM `bots`', ['fetch' => 'all']);
    }

    public function getOnlineOffline()
    {
        $out = [];
        $out['online'] = $this->query('SELECT count(*) FROM bots_online_v', ['fetch' => 'true']);
        $out['offline'] = $this->query('SELECT count(*) FROM bots_offline_v', ['fetch' => 'true']);
        $out['total'] = $this->query('SELECT count(*) FROM bots_online_offline_v', ['fetch' => 'true']);

        return $out;
    }

    public function getBotFiltered($filter, $max)
    {
        $out = [];
        $_tmp = $this->query('SELECT `id` FROM `bots` WHERE `os` LIKE :os LIMIT :mv', ['binded' => [':os' => '%'.$filter.'%', ':mv' => (string) $max], 'fetch' => 'all']);
        foreach ($_tmp as $value) {
            array_push($out, $value['id']);
        }

        return $out;
    }
}
