<?php

namespace Fuel\Migrations;

class Create_celebrities_professions
{
	public function up()
	{
		\DBUtil::create_table('celebrities_professions', array(
			'celebrity_id' => array('constraint' => 11, 'type' => 'int'),
			'profession_id' => array('constraint' => 11, 'type' => 'int'),

		), array('celebrity_id', 'profession_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('celebrities_professions');
	}
}