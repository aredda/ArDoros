<?php

class Authenticator
{
    public const ADMIN_NAME = 'omar';
    public const ADMIN_PASS = '123456';

    /**
     * if given information is valid, 'true' will be returned 
     */
    public static function authenticate ($name, $pass)
    {
        if (strcmp ($name, self::ADMIN_NAME) == 0 && strcmp ($pass, self::ADMIN_PASS) == 0)
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
    {
        session_start ();

        return isset ($_SESSION['admin']);
    }
}