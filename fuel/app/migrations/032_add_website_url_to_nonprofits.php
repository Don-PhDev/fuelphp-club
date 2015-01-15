<?php

namespace Fuel\Migrations;

class Add_website_url_to_nonprofits
{
	public function up()
	{
		\DBUtil::add_fields('nonprofits', array(
			'website_url' => array('constraint' => 50, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('nonprofits', array(
			'website_url'

		));
	}
}