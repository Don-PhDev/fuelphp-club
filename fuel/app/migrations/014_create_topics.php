<?php

namespace Fuel\Migrations;

class Create_topics
{
	public function up()
	{
		\DBUtil::create_table('topics', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'name' => array('constraint' => 35, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));

		\DB::query('INSERT INTO topics SET `name`="Members", `created_at`=UNIX_TIMESTAMP()')->execute();
		\DB::query('INSERT INTO topics SET `name`="Non Profits", `created_at`=UNIX_TIMESTAMP()')->execute();
		\DB::query('INSERT INTO topics SET `name`="Shopping", `created_at`=UNIX_TIMESTAMP()')->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('topics');
	}
}