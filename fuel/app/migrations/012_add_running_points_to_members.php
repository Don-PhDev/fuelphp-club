<?php

namespace Fuel\Migrations;

class Add_running_points_to_members
{
	public function up()
	{
		\DBUtil::add_fields('members', array(
			'running_points' => array('constraint' => 11, 'type' => 'int', 'default' => 0),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('members', array(
			'running_points'

		));
	}
}