<?php

namespace Fuel\Migrations;

class Create_members
{
	public function up()
	{
		\DBUtil::create_table('members', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'member_referer_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'status' => array('constraint' => 32, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'), true, array('InnoDB'), 'UTF8');
	}

	public function down()
	{
		\DBUtil::drop_table('members');
	}
}