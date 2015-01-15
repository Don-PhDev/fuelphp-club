<?php

namespace Fuel\Migrations;

class Push_celebrity_data
{
    public function up()
    {
	/**
	 * Data is in fuel/app/migrations/with.data-019_push_celebrity_data.php
	 */
    }

    public function down()
    {
        $ar = \Model_Celebrity::find()->get();

        foreach ($ar as $row)
        {
            foreach ($row->causes as $cause_id => $cause)
                unset($row->causes[$cause_id]);

            foreach ($row->nonprofits as $nonprofit_id => $nonprofit)
                unset($row->nonprofits[$nonprofit_id]);

            $row->save();
            $row->delete();
        }

        \DB::query('TRUNCATE celebrities;')->run();
    }
}
