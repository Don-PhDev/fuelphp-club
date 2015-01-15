<?php

class Model_Nonprofit_Address extends \Orm\Model
{
	protected static $_table_name = 'nonprofit_addresses';

	protected static $_properties = array(
		'id',
		'nonprofit_id',
		'city',
		'state',
		'country',
	);

	public static function validate($factory)
	{
		$val = Validation::forge('nonprofit_address'.$factory);

        if (is_numeric($factory))
        {
			$val->add_field('nprofit_city_'.$factory, 'City', 'required|max_length[35]');
			$val->add_field('nprofit_state_'.$factory, 'State', 'required|max_length[35]');
			$val->add_field('nprofit_country_'.$factory, 'Country', 'required|max_length[35]');
        }
		else
        {
			$val->add_field('nprofit_city', 'City', 'required|max_length[35]');
			$val->add_field('nprofit_state', 'State', 'required|max_length[35]');
			$val->add_field('nprofit_country', 'Country', 'required|max_length[35]');
        }

		return $val;
	}
}
