<?php

namespace Fuel\Migrations;

class Create_donors
{
	public function up()
	{
		\DBUtil::create_table('donors', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'name' => array('constraint' => 45, 'type' => 'varchar'),
			'image_filename' => array('constraint' => 100, 'type' => 'varchar'),
			'organization_name' => array('constraint' => 45, 'type' => 'varchar'),
			'organization_logo' => array('constraint' => 100, 'type' => 'varchar'),
			'organization_text' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('donors');
	}
}