<?php

namespace Fuel\Migrations;

class Create_donors_nonprofits
{
	public function up()
	{
		\DBUtil::create_table('donors_nonprofits', array(
			'donor_id' => array('constraint' => 11, 'type' => 'int'),
			'nonprofit_id' => array('constraint' => 11, 'type' => 'int'),

		), array('donor_id', 'nonprofit_id'), true, array('InnoDB'), 'UTF8');
	}

	public function down()
	{
		\DBUtil::drop_table('donors_nonprofits');
	}
}