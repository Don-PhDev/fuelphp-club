<?php

namespace Fuel\Migrations;

class Create_infoemails_members
{
	public function up()
	{
		\DBUtil::create_table('infoemails_members', array(
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'infoemail_id' => array('constraint' => 11, 'type' => 'int'),

		));

		\DB::query("ALTER TABLE `infoemails_members` ADD PRIMARY KEY ( `member_id`, `infoemail_id` )")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('infoemails_members');
	}
}