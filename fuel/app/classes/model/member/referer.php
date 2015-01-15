<?php

class Model_Member_Referer extends \Orm\Model
{
	protected static $_table_name = 'member_referers';

	protected static $_properties = array(
		'id',
		'first_name',
		'last_name'
	);

    protected static $_has_one = array(
        'referer' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member',
            'key_to' => 'member_referer_id',
            'cascade_save' => true,
        )
    );

    public static function validate()
    {
        $val = Validation::forge('referer');

        $val->add_field('referer_first_name', 'Referer First Name', 'required|max_length[25]');
        $val->add_field('referer_last_name', 'Referer Last Name', 'required|max_length[25]');

        return $val;
    }
}
