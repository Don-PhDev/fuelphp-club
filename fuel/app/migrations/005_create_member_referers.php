<?php

namespace Fuel\Migrations;

class Create_member_referers
{
	public function up()
	{
		\DBUtil::create_table('member_referers', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'first_name' => array('constraint' => 25, 'type' => 'varchar'),
			'last_name' => array('constraint' => 25, 'type' => 'varchar'),

		), array('id'), true, array('InnoDB'), 'UTF8');
	}

	public function down()
	{
		\DBUtil::drop_table('member_referers');
	}
}