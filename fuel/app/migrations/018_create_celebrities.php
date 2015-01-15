<?php

namespace Fuel\Migrations;

class Create_celebrities
{
	public function up()
	{
		\DBUtil::create_table('celebrities', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'first_name' => array('constraint' => 35, 'type' => 'varchar'),
			'last_name' => array('constraint' => 35, 'type' => 'varchar'),
			'image_filename' => array('constraint' => 100, 'type' => 'varchar'),

		), array('id'), true, array('InnoDB'), 'UTF8');

		\DBUtil::create_table('celebrities_members', array(
			'celebrity_id' => array('constraint' => 11, 'type' => 'int'),
			'member_id' => array('constraint' => 11, 'type' => 'int'),

		), array('celebrity_id', 'member_id'), true, array('InnoDB'), 'UTF8');

		\DBUtil::create_table('celebrities_nonprofits', array(
			'celebrity_id' => array('constraint' => 11, 'type' => 'int'),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),

		), array('celebrity_id', 'nonprofit_id'), true, array('InnoDB'), 'UTF8');

		\DBUtil::create_table('causes_celebrities', array(
			'cause_id' => array('constraint' => 11, 'type' => 'int'),
			'celebrity_id' => array('constraint' => 11, 'type' => 'int'),

		), array('cause_id', 'celebrity_id'), true, array('InnoDB'), 'UTF8');
	}

	public function down()
	{
		\DBUtil::drop_table('celebrities');
		\DBUtil::drop_table('celebrities_members');
		\DBUtil::drop_table('celebrities_nonprofits');
		\DBUtil::drop_table('causes_celebrities');
	}
}