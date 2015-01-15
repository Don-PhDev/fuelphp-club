<?php

class Model_Comment extends \Orm\Model
{
	protected static $_table_name = 'comments';

	protected static $_properties = array(
		'id',
		'topic_id',
		'title',
		'message',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

	protected static $_belongs_to = array(
		'topic' => array(
	    	'key_from' => 'topic_id',
	    	'model_to' => 'Model_Topic',
	    	'key_to' => 'id',
	        'cascade_save' => true,
	    ),
	);

	public static function validate($factory = null)
	{
		$val = Validation::forge($factory);

		$val->add_field('title_members', "title on members", 'required|max_length[100]');
		$val->add_field('title_nonprofits', "title on non profits", 'required|max_length[100]');
		$val->add_field('title_shopping', "title on shopping", 'required|max_length[100]');

		$val->add_field('members', "members what's new", 'required|min_length[25]');
		$val->add_field('nonprofits', "non profits what's new", 'required|min_length[25]');
		$val->add_field('shopping', "shopping what's new", 'required|min_length[25]');

		return $val;
	}
}
