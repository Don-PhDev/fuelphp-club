<?php

namespace Fuel\Migrations;

class Create_infoemails
{
	public function up()
	{
		\DBUtil::create_table('infoemails', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'email_type' => array('constraint' => 30, 'type' => 'varchar'),
			'email_subject' => array('constraint' => 100, 'type' => 'varchar'),
			'email_text' => array('type' => 'text'),
			'delivery_schedule' => array('constraint' => 7, 'type' => 'varchar'),
			'is_active' => array('constraint' => 1, 'type' => 'tinyint'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('infoemails');
	}
}