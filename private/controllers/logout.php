<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 1/25/2016
 * Time: 2:34 PM
 */
class Logout extends Controller
{
    function index()
    {
        session_destroy();
        $this->redirect('login');
    }
}