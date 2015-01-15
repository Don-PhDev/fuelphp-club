<?php

namespace Fuel\Migrations;

class Create_nonprofit_addresses
{
	public function up()
	{
		\DBUtil::create_table('nonprofit_addresses', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),
			'city' => array('constraint' => 35, 'type' => 'varchar'),
			'state' => array('constraint' => 35, 'type' => 'varchar'),

		), array('id'), true, array('InnoDB'), 'UTF8');
	}

	public function down()
	{
		\DBUtil::drop_table('nonprofit_addresses');
	}
}