<?php

namespace Fuel\Migrations;

class Create_professions
{
	public function up()
	{
		\DBUtil::create_table('professions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'field' => array('constraint' => 35, 'type' => 'varchar'),

		), array('id'));
		
		\DB::query("INSERT INTO `professions` SET field = 'Business'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Comedy'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Dance'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Exploration'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Fashion'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Food'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Health'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Journalism'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Literature'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Magic'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Military'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Movies'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Music'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Politics'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Radio'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Religion'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Royalty'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Science'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Society'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Sports'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Television'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Theater'")->execute();
		\DB::query("INSERT INTO `professions` SET field = 'Visual'")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('professions');
	}
}