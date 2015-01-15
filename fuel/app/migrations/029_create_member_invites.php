<?php

namespace Fuel\Migrations;

class Create_member_invites
{
	public function up()
	{
		\DBUtil::create_table('member_invites', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'email' => array('constraint' => 100, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));

		\DB::query("ALTER TABLE `member_invites` ADD INDEX ( `member_id` )")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('member_invites');
	}
}