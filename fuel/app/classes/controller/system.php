<?php

class Controller_System extends Controller_Base
{
    public function action_phpinfo()
    {
        phpinfo();
        exit;
    }

    public static function ip()
    {
        return exec('python ../findip.py');
    }
}

// eof
