<?php

/**
 * Get uri request
 */

$system = "";

if (isset($_SERVER['REQUEST_URI']))
{
    $uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

    if ($uri[0] == "admin")
        $system = "admin/";
}

return array(
	'_root_'  => 'home',
	'_404_'   => $system.'system/404',

    'admin' => 'admin/index',

    'signin' => 'auth/signin',
    'signout' => 'auth/signout',
    'login' => 'auth/signin',
    'logout' => 'auth/signout',

	'registration' => 'members/registration',
);