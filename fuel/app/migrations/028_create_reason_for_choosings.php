<?php

namespace Fuel\Migrations;

class Create_reason_for_choosings
{
	public function up()
	{
		\DBUtil::create_table('reasons_for_choosing', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),
			'reason' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));

		\DB::query("ALTER TABLE `reasons_for_choosing` ADD UNIQUE (`member_id`, `nonprofit_id`)")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('reasons_for_choosing');
	}
}