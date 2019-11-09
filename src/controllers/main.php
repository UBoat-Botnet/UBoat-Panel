<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Main extends Controller
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
        $template = $this->loadView('main_view');
        $r = $this->route();
        $headerview = $this->loadView('header')->render(['route' => (strstr($r, '?')) ? explode('?', $r)[0] : $r]);
        //update bots
        //$_bots = $this->loadModel("bots");
        //$_bots->updateBots();

        $template->render(['paginate' => $this->loadHelper('paginate'), 'header' => $headerview, 'query' => 'SELECT * FROM bots_online_offline_v']);
    }
}
