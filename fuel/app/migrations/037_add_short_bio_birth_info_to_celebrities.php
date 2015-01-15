<?php

namespace Fuel\Migrations;

class Add_short_bio_birth_info_to_celebrities
{
	public function up()
	{
		\DBUtil::add_fields('celebrities', array(
			'short_bio' => array('constraint' => 500, 'type' => 'varchar', 'null' => true),
			'birth_info' => array('constraint' => 200, 'type' => 'varchar', 'null' => true),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('celebrities', array(
			'short_bio',
			'birth_info'

		));
	}
}