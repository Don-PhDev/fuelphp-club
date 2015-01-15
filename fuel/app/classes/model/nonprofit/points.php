<?php

class Model_Nonprofit_Points extends \Orm\Model
{
	protected static $_table_name = 'nonprofit_points';

	protected static $_properties = array(
		'id',
		'nonprofit_id',
		'points',
		'source',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt');
}
