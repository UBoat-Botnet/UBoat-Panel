<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class Session_helper
{
    public function set($key, $val)
    {
        $_SESSION["$key"] = $val;
    }

    public function get($key)
    {
        return $_SESSION["$key"];
    }

    public function destroy()
    {
        session_destroy();
    }
}
