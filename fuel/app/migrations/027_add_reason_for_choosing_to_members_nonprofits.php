<?php

namespace Fuel\Migrations;

class Add_reason_for_choosing_to_members_nonprofits
{
	public function up()
	{
		\DBUtil::add_fields('members_nonprofits', array(
			'reason_for_choosing' => array('type' => 'text'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('members_nonprofits', array(
			'reason_for_choosing'

		));
	}
}