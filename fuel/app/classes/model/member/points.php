<?php

class Model_Member_Points extends \Orm\Model
{
	protected static $_table_name = 'member_points';

	protected static $_properties = array(
		'id',
		'member_id',
		'points',
		'source',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt');
}
