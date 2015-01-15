<?php

class Model_Profession extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'field',
    );

    protected static $_many_many = array(
        'celebrities' => array(
            'key_through_from' => 'profession_id',
            'table_through' => 'celebrities_professions',
            'key_through_to' => 'celebrity_id',
            'model_to' => 'Model_Celebrity',
            'cascade_save' => true,
        ),
    );

    public static function get_model_autosave($field)
    {
        $row = self::find()->where('field', $field)->get_one();

        if ($row)
            return $row;
        else
        {
            $ar = self::forge(array('field' => $field));
            if ($ar->save())
                return $ar;
            else
                trigger_error("Model_Profession::get_model_autosave('$field') failed.", E_USER_ERROR);
        }
    }
}
