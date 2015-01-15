<?php

class Model_Message extends \Orm\Model
{
	protected static $_table_name = 'messages';

	protected static $_properties = array(
		'id',
		'name',
        'regarding',
        'message',
		'email',
		'phone_number',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt');

    public static function validate($factory = null)
    {
        $val = Validation::forge($factory);

        $val->add_field('regarding', "Subject", 'required');
        $val->add_field('message', "Message", 'required|min_length[50]');
        $val->add_field('email', "Email", 'required|valid_email');
        $val->add_field('first_name', "First name", 'required|min_length[2]');

        return $val;
    }
}
