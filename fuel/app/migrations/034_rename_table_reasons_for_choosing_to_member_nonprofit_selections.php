<?php

namespace Fuel\Migrations;

class Rename_table_reasons_for_choosing_to_member_nonprofit_selections
{
	public function up()
	{
		\DBUtil::rename_table('reasons_for_choosing', 'member_nonprofit_selections');
	}

	public function down()
	{
		\DBUtil::rename_table('member_nonprofit_selections', 'reasons_for_choosing');
	}
}