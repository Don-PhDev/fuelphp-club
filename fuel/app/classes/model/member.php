<?php

class Model_Member extends \Orm\Model
{
    protected static $_table_name = 'members';

    protected static $_properties = array(
        'id',
        'user_id',
        'member_referer_id' => array('default' => null),
        'running_points' => array('default' => 0),
        'status',
        'created_at',
        'updated_at',
    );

    protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

    protected static $_has_one = array(
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
            'cascade_save' => true,
        ),
        'referer' => array(
            'key_from' => 'member_referer_id',
            'model_to' => 'Model_Member_Referer',
            'key_to' => 'id',
            'cascade_save' => true,
        ),
        'address' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member_Address',
            'key_to' => 'member_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        ),
    );

    protected static $_has_many = array(
        'points' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member_Points',
            'key_to' => 'member_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        ),
        'nonprofit_selections' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member_Nonprofit_Selections',
            'key_to' => 'member_id',
            'cascade_save' => true,
        ),
        'authored_comments' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member_Comment',
            'key_to' => 'commenting_member_id',
            'cascade_save' => true,
        ),
        'received_comments' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member_Comment',
            'key_to' => 'member_id',
            'cascade_save' => true,
        ),
    );

    protected static $_many_many = array(
        'nonprofits' => array(
            'key_from' => 'id',
            'key_through_from' => 'member_id',
            'table_through' => 'members_nonprofits',
            'key_through_to' => 'nonprofit_id',
            'model_to' => 'Model_Nonprofit',
            'key_to' => 'id',
            'cascade_save' => true,
        ),
        'celebrities' => array(
            'key_through_from' => 'member_id',
            'table_through' => 'celebrities_members',
            'key_through_to' => 'celebrity_id',
            'model_to' => 'Model_Celebrity',
            'cascade_save' => true,
        ),
        'infomails' => array(
            'key_through_from' => 'member_id',
            'table_through' => 'infoemails_members',
            'key_through_to' => 'infoemail_id',
            'model_to' => 'Model_Infoemail',
            'cascade_save' => true,
        ),
    );
}
