<?php

class Model_Infoemail extends \Orm\Model
{
	protected static $_table_name = 'infoemails';

	protected static $_properties = array(
		'id',
		'email_type',
		'email_subject',
		'email_text',
		'delivery_schedule',
		'is_active',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

	public static function validate()
	{
		$val = Validation::forge('infoemail');

		$val->add_field('subject', 'Subject', 'required|min_length[20]');
		$val->add_field('email_text', 'Email text', 'required|min_length[100]');

		return $val;
	}
}
