<?php

namespace Fuel\Migrations;

class Create_member_addresses
{
	public function up()
	{
		\DBUtil::create_table('member_addresses', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'line_1' => array('constraint' => 45, 'type' => 'varchar'),
			'line_2' => array('constraint' => 45, 'type' => 'varchar'),
			'city' => array('constraint' => 35, 'type' => 'varchar'),
			'state' => array('constraint' => 35, 'type' => 'varchar'),
			'zip' => array('constraint' => 5, 'type' => 'CHAR'),

		), array('id'), true, array('InnoDB'), 'UTF8');
	}

	public function down()
	{
		\DBUtil::drop_table('member_addresses');
	}
}