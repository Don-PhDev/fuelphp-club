<?php

class Model_Member_Invite extends \Orm\Model
{
	protected static $_table_name = 'member_invites';

	protected static $_properties = array(
		'id',
		'member_id',
		'email',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt');
}
