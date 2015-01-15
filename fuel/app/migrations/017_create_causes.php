<?php

namespace Fuel\Migrations;

class Create_causes
{
	public function up()
	{
		\DBUtil::create_table('causes', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'cause' => array('constraint' => 35, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('causes');
	}
}