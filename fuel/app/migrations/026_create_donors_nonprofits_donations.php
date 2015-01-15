<?php

namespace Fuel\Migrations;

class Create_donors_nonprofits_donations
{
	public function up()
	{
		\DBUtil::create_table('donors_nonprofits_donations', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'donor_id' => array('constraint' => 11, 'type' => 'int'),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),
			'amount' => array('constraint' => '10,2', 'type' => 'decimal'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('donors_nonprofits_donations');
	}
}