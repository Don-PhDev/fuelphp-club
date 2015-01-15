<?php

namespace Fuel\Migrations;

class Create_infoemails_members_logs
{
	public function up()
	{
		\DBUtil::create_table('infoemails_members_logs', array(
			'member_id' => array('constraint' => 11, 'type' => 'int'),
			'infoemail_id' => array('constraint' => 11, 'type' => 'int'),
			'send_at' => array('constraint' => 11, 'type' => 'int'),
		));
	}

	public function down()
	{
		\DBUtil::drop_table('infoemails_members_logs');
	}
}