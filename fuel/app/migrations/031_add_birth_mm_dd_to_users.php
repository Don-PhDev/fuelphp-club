<?php

namespace Fuel\Migrations;

class Add_birth_mm_dd_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
			'birth_mm_dd' => array('constraint' => 5, 'type' => 'char', 'default' => null),

		));
		\DB::query("ALTER TABLE  `users` CHANGE  `birth_mm_dd`  `birth_mm_dd` CHAR(5) NULL DEFAULT NULL")->execute();
		\DB::query("UPDATE  `users` SET  `birth_mm_dd` = NULL")->execute();

		\DB::query("ALTER TABLE  `users` CHANGE  `birth_date`  `birth_date` DATE NULL DEFAULT NULL")->execute();
	}

	public function down()
	{
		\DBUtil::drop_fields('users', array(
			'birth_mm_dd'

		));

		\DB::query("ALTER TABLE  `users` CHANGE  `birth_date`  `birth_date` DATE NOT NULL")->execute();
	}
}