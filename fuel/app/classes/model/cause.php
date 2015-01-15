<?php

class Model_Cause extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'cause'
	);

    protected static $_many_many = array(
        'celebrities' => array(
            'key_through_from' => 'cause_id',
            'table_through' => 'causes_celebrities',
            'key_through_to' => 'celebrity_id',
            'model_to' => 'Model_Celebrity',
            'cascade_save' => true,
        ),
    );

    public static function get_id_autosave($cause)
    {
        $row = self::find()->where('cause', $cause)->get_one();

        if ($row)
            return $row->id;
        else
        {
            $ar = self::forge(array('cause' => $cause));
            if ($ar->save())
                return $ar->id;
            else
                trigger_error("Model_Cause::get_id_autosave('$cause') failed.", E_USER_ERROR);
        }
    }

    public static function get_model_autosave($cause)
    {
        $row = self::find()->where('cause', $cause)->get_one();

        if ($row)
            return $row;
        else
        {
            $ar = self::forge(array('cause' => $cause));
            if ($ar->save())
                return $ar;
            else
                trigger_error("Model_Cause::get_model_autosave('$cause') failed.", E_USER_ERROR);
        }
    }
}
