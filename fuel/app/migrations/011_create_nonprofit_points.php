<?php

namespace Fuel\Migrations;

class Create_nonprofit_points
{
	public function up()
	{
		\DBUtil::create_table('nonprofit_points', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),
			'points' => array('constraint' => 11, 'type' => 'int'),
			'source' => array('constraint' => 20, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('nonprofit_points');
	}
}