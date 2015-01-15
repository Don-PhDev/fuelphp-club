<?php

namespace Fuel\Migrations;

class Add_country_to_nonprofit_address
{
	public function up()
	{
		\DBUtil::add_fields('nonprofit_addresses', array(
			'country' => array('constraint' => 35, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('nonprofit_addresses', array(
			'country'

		));
	}
}