<?php

class Model_Topic extends \Orm\Model
{
	protected static $_table_name = 'topics';

	protected static $_properties = array(
		'id',
		'name',
		'created_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

	protected static $_has_many = array(
		'comments' => array(
	    	'key_from' => 'id',
	    	'model_to' => 'Model_Comment',
	    	'key_to' => 'topic_id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    ),
	);

	public static function get_latest_comments()
	{
        $comments = array();
        $topic_count = Model_Topic::find()->count();
        $ar_comments = Model_Comment::find()->order_by('created_at', 'desc')->get();

        foreach ($ar_comments as $comment)
        {
            if (count($comments) == $topic_count)
                break;

            if ( ! isset($comments[$comment->topic_id]))
                $comments[$comment->topic_id] = $comment;
        }

        return $comments;
	}
}
