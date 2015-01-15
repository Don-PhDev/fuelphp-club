<?php

namespace Fuel\Migrations;

class Create_member_comments
{
	public function up()
	{
		\DBUtil::create_table('member_comments', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'commenting_member_id' => array('constraint' => 11, 'type' => 'int'),
			'comment' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));

		\DB::query("ALTER TABLE `member_comments` ADD INDEX ( `member_id` )")->execute();
		\DB::query("ALTER TABLE `member_comments` ADD INDEX ( `commenting_member_id` )")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('member_comments');
	}
}