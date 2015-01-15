<?php

namespace Fuel\Migrations;

class Add_is_selected_to_nonprofits
{
	public function up()
	{
		\DBUtil::add_fields('nonprofits', array(
			'is_contacted' => array('constraint' => 1, 'type' => 'tinyint', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('nonprofits', array(
			'is_contacted'

		));
	}
}