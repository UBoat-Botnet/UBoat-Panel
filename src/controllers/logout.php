<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Logout extends Controller
{
    public function index()
    {
        session_destroy();
        $this->redirect('login');
    }
}
