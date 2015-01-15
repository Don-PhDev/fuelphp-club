<?php

class Model_Nonprofit extends \Orm\Model
{
    protected static $_table_name = 'nonprofits';

    protected static $_properties = array(
        'id',
        'name',
        'running_points' => array('default' => 0),
        'info' => array('default' => ''),
        'website_url' => array('default' => ''),
        'is_contacted' => array('default' => 0),
        'created_at',
        'updated_at',
    );

    protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

    protected static $_has_one = array(
        'address' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Nonprofit_Address',
            'key_to' => 'nonprofit_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );

    protected static $_has_many = array(
        'points' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Nonprofit_Points',
            'key_to' => 'nonprofit_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );

    protected static $_many_many = array(
        'celebrities' => array(
            'key_through_from' => 'nonprofit_id',
            'table_through' => 'celebrities_nonprofits',
            'key_through_to' => 'celebrity_id',
            'model_to' => 'Model_Celebrity',
            'cascade_save' => true,
        ),
    );

    public static function validate($factory)
    {
        $val = Validation::forge('nonprofit'.$factory);

        if (is_numeric($factory))
            $val->add_field('nprofit_name_'.$factory, 'Name', 'required');
        else
            $val->add_field('nprofit_name', 'Name', 'required');

        return $val;
    }

    public static function get_id_autosave($nonprofit)
    {
        $row = self::find()->where('name', $nonprofit)->get_one();

        if ($row)
            return $row->id;
        else
        {
            $ar = self::forge(array('name' => $nonprofit));
            if ($ar->save())
                return $ar->id;
            else
                trigger_error("Model_Nonprofit::get_id_autosave('$nonprofit') failed.", E_USER_ERROR);
        }
    }

    public static function get_model_autosave($nonprofit)
    {
        $row = self::find()->where('name', $nonprofit)->get_one();

        if ($row)
            return $row;
        else
        {
            $ar = self::forge(array('name' => $nonprofit));
            if ($ar->save())
                return $ar;
            else
                trigger_error("Model_Nonprofit::get_model_autosave('$nonprofit') failed.", E_USER_ERROR);
        }
    }

    public static function get_nonprofit_arr()
    {
        $arr = array();
        $rows = DB::query('SELECT name FROM nonprofits ORDER BY name')->execute();
        foreach ($rows as $row)
            $arr[] = $row['name'];
        return $arr;
    }

    public static function get_selecting_member($id = null)
    {
        if (is_null($id))
            return false;

        $sql = "SELECT u.id, u.first_name, u.last_name
            FROM nonprofits AS np
                INNER JOIN member_nonprofit_selections AS s ON s.nonprofit_id = np.id
                INNER JOIN members AS m ON m.id = s.member_id
                INNER JOIN users AS u ON u.id = m.user_id
            WHERE np.id = $id
            ORDER BY s.created_at DESC
            LIMIT 1";

        if ($row = DB::query($sql)->execute())
            return array(
                'id' => $row[0]['id'],
                'first_name' => $row[0]['first_name'],
                'last_name' => $row[0]['last_name'],
            );
        else
            return false;
    }
}
