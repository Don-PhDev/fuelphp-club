<?php

class Model_Member_Nonprofit_Selections extends \Orm\Model
{
    protected static $_table_name = 'member_nonprofit_selections';

    protected static $_properties = array(
        'id',
        'member_id',
        'nonprofit_id',
        'reason' => array('default' => ''),
        'created_at',
        'updated_at',
    );

    protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

    public static function get_nonprofit_selections($user_id)
    {   
        $arr = array();
        
        $result = self::query()->where(array('member_id', $user_id))->get();

        foreach ($result as $row)
            $arr[$row->nonprofit_id] = $row->reason;

        return $arr;
    }
}
