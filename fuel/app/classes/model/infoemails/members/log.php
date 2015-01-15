<?php

class Model_Infoemails_Members_Log extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'member_id',
		'infoemail_id'
	);

	protected static $_observers = array(
		'\Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
			'property' => 'send_at',
		),
	);
}
