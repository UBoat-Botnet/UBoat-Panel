<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Errors extends Controller
{
    public function index()
    {
        $this->error404();
    }

    public function error404()
    {
        http_response_code(404);
        echo '<h1>404 Error</h1>';
        echo '<p>Looks like this page doesn\'t exist</p>';
        echo $_SERVER['REQUEST_URI'];
    }

    public function error403()
    {
        http_response_code(403);
        echo '<h1>403 Forbidden </h1>';
        echo '<p>You are not allowed to perform this action.</p>';
    }
}
