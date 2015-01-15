<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'username' => array('constraint' => 50, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'group' => array('constraint' => 11, 'type' => 'int'),
			'email' => array('constraint' => 255, 'type' => 'varchar'),
			'last_login' => array('constraint' => 25, 'type' => 'varchar'),
			'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
			'profile_fields' => array('type' => 'TEXT'),
			'first_name' => array('constraint' => 25, 'type' => 'varchar'),
			'last_name' => array('constraint' => 25, 'type' => 'varchar'),
			'alias' => array('constraint' => 25, 'type' => 'varchar'),
			'birth_date' => array('type' => 'DATE'),
			'phone_number' => array('constraint' => 15, 'type' => 'varchar'),
			'image_filename' => array('constraint' => 255, 'type' => 'varchar'),
			'gender' => array('constraint' => "'M','F',''", 'type' => 'enum', 'default' => ''),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'), true, array('InnoDB'), 'UTF8');

		\DB::query("ALTER TABLE `users` ADD UNIQUE (`username`)")->execute();
		\DB::query("ALTER TABLE `users` ADD UNIQUE (`email`)")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}