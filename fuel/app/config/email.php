<?php

return array(

    'defaults' => array(
        'is_html' => true,
        'driver' => 'smtp',
        'smtp' => array(
            'host' => 'ssl://smtp.gmail.com',
            'port' => 465,
            'username' => 'tester@philippinedev.com',
            'password' => 'Default1',
            'timeout' => 5  // this can be whatever you want
        ),
        'newline' => "\r\n",
    ),

    /**
     * Default setup group
     */
    'default_setup' => 'defaults',

    /**
     * Setup groups
     */
    'setups' => array(
        'default' => array(),
    ),

);
