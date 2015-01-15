<?php

class Model_User extends \Orm\Model
{
    protected static $_table_name = 'users';

    protected static $_properties = array(
        'id',
        'username',
        'password',
        'group',
        'email',
        'last_login' => array('default' => '0'),
        'login_hash' => array('default' => '0'),
        'profile_fields' => array('default' => '0'),
        'first_name',
        'last_name',
        'alias',
        'birth_date',
        'birth_mm_dd',
        'phone_number' => array('default' => ''),
        'image_filename' => array('default' => ''),
        'gender',
        'created_at',
        'updated_at',
    );

    protected static $_observers = array('\Orm\Observer_CreatedAt', '\Orm\Observer_UpdatedAt');

    protected static $_has_one = array(
        'member' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Member',
            'key_to' => 'user_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );

    public static function validate()
    {
        $val = Validation::forge('user');

        $val->add_field('password', 'Password', 'required|max_length[255]');
        $val->add_field('email', 'Email', 'required|valid_email');
        $val->add_field('first_name', 'First Name', 'required|max_length[25]');
        $val->add_field('last_name', 'Last Name', 'required|max_length[25]');
        $val->add_field('alias', 'Alias', 'required|max_length[25]');

        // $val->add_field('birth_date', 'Birth Date', 'required|min_length[5]|max_length[10]');
        $val->add_field('birth_date_day', 'Birth Day', 'required');
        $val->add_field('birth_date_month', 'Birth Month', 'required');

        return $val;
    }

    public static function validate_password()
    {
        $val = Validation::forge('password');

        $val->add_callable(new MyRules());

        $val->add_field('password', 'Password', 'required|min_length[6]|max_length[255]')
            ->add_rule('should_be_the_same');
        $val->add_field('confirm_password', 'Confirm Password', 'required|min_length[6]|max_length[255]')
            ->add_rule('should_be_the_same');

        return $val;
    }

    public static function generate_unique_username($username)
    {
        $username = str_replace(' ', '', $username);
        
        $matches = self::query()->where(array('username', 'like', "$username%"))->get();    

        if (count($matches) == 0)
            return $username;

        $int = 0;

        foreach ($matches as $match)
        {
            $this_int = (int) Helper::extract_integer($match->username);
            if ($this_int > $int)
                $int = $this_int;
        }
        return $username . ($int + 1);
    }

    public static function generate_birthday_celebrants()
    {
        $sql = "SELECT u.first_name, u.last_name, u.email, m.id
            FROM users AS u
            INNER JOIN members AS m ON (m.user_id = u.id)
            WHERE EXTRACT(MONTH FROM birth_date) = " . date('m');

        $query = DB::query($sql)->execute();

        $birthday_users = array();
        foreach ($query as $row)
        {
            $row['avatar'] = self::get_avatar($row['id'], 74);
            $birthday_users[] = $row;
        }

        return $birthday_users;
    }

    public static function get_avatar($id, $size = 122)
    {
        $user = self::find($id);
        if ($user) // && property_exists($user, 'email'))
            return Helper::get_avatar($id, $user->email, $size);

        return 'http://' . $_SERVER['HTTP_HOST'] . '/assets/img/no_image_2.jpg';
    }

    public static function get_superuser_emails()
    {
        $emails = array();

        $query = self::find()->where('group', 999)->get();
        foreach ($query as $row)
        {
            $emails[] = array(
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
            );
        }

        return $emails;
    }

    public static function create_user($group = null, $name = null, $email = null)
    {
        if ( ! $group || ! $name || ! $email)
            return array(false, 'Incomplete parameter');

        $username = self::generate_unique_username($name);

        $user = new Model_User(array(
            'username' => $username,
            'alias' => $username,
            'password' => Auth::instance()->hash_password($username),
            'group' => $group,
            'email' => $email,
            'first_name' => "",
            'last_name' => "",
            'birth_date' => '1970-01-01',
            'gender' => "",
        ));

        $user->member = new Model_Member(array(
            'status' => md5($email),
        ));

        try {
            $result = $user->save();
        }
        catch (\Database_Exception $e)
        {
            if ($e->getCode() == 23000)
                return array(false, 'Duplicate email');
            else
                return array(false, 'Unknown error');
        } 
        return array(true);
    }
}

// eof
