<?php

class Authenticator
{
    public const ADMIN_NAME = 'omar';
    public const ADMIN_PASS = '123456';

    /**
     * if given information is valid, 'true' will be returned 
     */
    public static function authenticate ($name, $pass)
        : bool
    {
        if (strcmp ($name, '') == 0 && strcmp ($pass, ''))
        {
            session_start ();

            $_SESSION['admin'] = [
                'name' => $name,
                'pass' => $pass
            ];
            
            return true;
        }

        return false;
    }

    /**
     * checks if there's a session or not
     */
    public static function isAuthenticated ()
        : bool
    {
        session_start ();

        return isset ($_SESSION['admin']);
    }
}