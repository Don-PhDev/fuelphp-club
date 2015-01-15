<?php

namespace Fuel\Migrations;

class Create_members_nonprofits
{
	public function up()
	{
		\DBUtil::create_table('members_nonprofits', array(
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),

		), null, true, array('InnoDB'), 'UTF8');

		\DB::query("ALTER TABLE `members_nonprofits` ADD PRIMARY KEY ( `member_id`, `nonprofit_id` )")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('members_nonprofits');
	}
}