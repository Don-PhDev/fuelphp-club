<?php

namespace Fuel\Migrations;

class Create_messages
{
	public function up()
	{
		\DBUtil::create_table('messages', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'name' => array('constraint' => 45, 'type' => 'varchar'),
			'regarding' => array('constraint' => 45, 'type' => 'varchar'),
			'message' => array('type' => 'text'),
			'email' => array('constraint' => 100, 'type' => 'varchar'),
			'phone_number' => array('constraint' => 12, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('messages');
	}
}