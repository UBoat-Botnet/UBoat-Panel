<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class dashboard extends Controller
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
        $template = $this->loadView('dashboard');
        $r = $this->route();
        $headerview = $this->loadView('header')->render(['route' => (strstr($r, '?')) ? explode('?', $r)[0] : $r]);
        //load bot
        $bot = $this->loadModel('bots');
        //load countries
        $countries = $bot->getCountries();
        $out = [];
        foreach ($countries as $_country) {
            if (array_key_exists($_country['country_code'], $out)) {
                $out[$_country['country_code']] = (int) $out[$_country['country_code']]++;
            } else {
                $out[$_country['country_code']] = 1;
            }
        }

        //get dos versions
        $_allos = $bot->getOS();
        $osdata = [
            'Windows Vista' => 0,
            'Windows XP' => 0,
            'Windows 7' => 0,
            'Windows 8' => 0,
            'Windows 8.1' => 0,
            'Windows 10' => 0,
            'Other' => 0,
        ];
        foreach ($_allos as $os) {
            $matches = null;
            preg_match("/Windows (\w+)/", $os['os'], $matches);
            if (null == $matches) {
                (int) $osdata['Other']++;
            }

            if (isset($matches[0]) && isset($osdata[$matches[0]])) {
                (int) $osdata[$matches[0]]++;
            } else {
                (int) $osdata['Other']++;
            }
        }
        $template->render(['header' => $headerview, 'countries' => json_encode($out), 'os_data' => $osdata, 'on_off' => $bot->getOnlineOffline()]);
    }
}
