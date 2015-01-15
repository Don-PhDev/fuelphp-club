<?php

class Model_Benefactor extends \Orm\Model
{
	protected static $_table_name = 'benefactors';

	protected static $_properties = array(
		'id',
		'name',
		'image_filename',
		'organization_name',
		'organization_logo',
		'organization_text',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

	public static function validate($factory = null)
	{
		$val = Validation::forge($factory);

		$val->add_field('name', "Name", 'required|min_length[3]|max_length[45]');
		$val->add_field('organization_name', "Organization", 'required|min_length[3]|max_length[45]');
		$val->add_field('about', "About", 'required|min_length[45]');

		return $val;
	}
}
