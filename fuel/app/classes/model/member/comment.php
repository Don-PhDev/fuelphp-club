<?php

class Model_Member_Comment extends \Orm\Model
{
	protected static $_table_name = 'member_comments';

	protected static $_properties = array(
		'id',
		'member_id',
		'commenting_member_id',
		'comment',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt');

	protected static $_belongs_to = array(
		'authoring_member' => array(
	    	'key_from' => 'commenting_member_id',
	    	'model_to' => 'Model_Member',
	    	'key_to' => 'id',
	        'cascade_save' => true,
	    ),
		'receiving_member' => array(
	    	'key_from' => 'member_id',
	    	'model_to' => 'Model_Member',
	    	'key_to' => 'id',
	        'cascade_save' => true,
	    ),
	);

    public static function validate($factory = null)
    {
        $val = Validation::forge($factory);

        $val->add_field('comment', "Comment", 'required|min_length[15]');

        return $val;
    }
}
