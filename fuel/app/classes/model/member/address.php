<?php

class Model_Member_Address extends \Orm\Model
{
	protected static $_table_name = 'member_addresses';

	protected static $_properties = array(
		'id',
		'member_id',
		'line_1' => array('default' => ''),
		'line_2' => array('default' => ''),
		'city',
		'state',
		'zip' => array('default' => ''),
		'country',
	);

	public static function validate()
	{
		$val = Validation::forge('member_address');

		$val->add_field('city', 'City', 'required|max_length[35]');
		$val->add_field('state', 'State', 'required|max_length[35]');
		$val->add_field('country', 'Country', 'required|max_length[35]');

		return $val;
	}

	public static function get_country_code($country)
	{
		return Model_Static::get_country_code($country);
	}
}
