<?php

class Model_Celebrity extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'first_name',
		'last_name',
        'image_filename',
	);

    protected static $_many_many = array(
        'members' => array(
            'key_through_from' => 'celebrity_id',
            'table_through' => 'celebrities_members',
            'key_through_to' => 'member_id',
            'model_to' => 'Model_Member',
            'cascade_save' => true,
        ),
        'nonprofits' => array(
            'key_through_from' => 'celebrity_id',
            'table_through' => 'celebrities_nonprofits',
            'key_through_to' => 'nonprofit_id',
            'model_to' => 'Model_Nonprofit',
            'cascade_save' => true,
        ),
        'causes' => array(
            'key_through_from' => 'celebrity_id',
            'table_through' => 'causes_celebrities',
            'key_through_to' => 'cause_id',
            'model_to' => 'Model_Cause',
            'cascade_save' => true,
        ),
        'professions' => array(
            'key_through_from' => 'celebrity_id',
            'table_through' => 'celebrities_professions',
            'key_through_to' => 'profession_id',
            'model_to' => 'Model_Profession',
            'cascade_save' => true,
        ),
    );

    public static function validate($factory = null)
    {
        $val = Validation::forge($factory);

        $val->add_field('name', "Name", 'required|min_length[3]|max_length[45]');

        return $val;
    }

    public static function get_id_autosave($first_name, $last_name, $image_filename)
    {
        $row = self::find()
            ->where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->get_one();

        if ($row)
            return $row->id;
        else
        {
            $ar = self::forge(array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'image_filename' => $image_filename,
            ));
            if ($ar->save())
                return $ar->id;
            else
                trigger_error("Model_Celebrity::get_id_autosave('$first_name', '$last_name', '$image_filename') failed.", E_USER_ERROR);
        }
    }

    public static function get_random($letter, $count = 10)
    {
        $rows = DB::query("SELECT id FROM celebrities WHERE first_name LIKE '$letter%'")->execute();

        $total = $n = $rows->count();
        $random_rows = array();

        while ($count > 0 && $n > 0)
        {
            $random = rand(0, $total);
            if ( ! array_key_exists($random, $random_rows))
            {
                if ($rows[$random]['id'])
                {
                    $random_rows[$random] = $rows[$random]['id'];
                    $count--;
                    $n--;
                }
            }
        }
        
        return self::find()->where('id', 'IN', DB::expr('('.implode(',', $random_rows).')'))->get();
    }

    public static function get_celebrities_on_letters($letter = null, $limit = null)
    {
        $celebrities = array();

        $query = self::find();
        if ($letter)
            $query->where(array('first_name', 'LIKE', "$letter%"));
        $query->order_by('first_name', 'asc')->order_by('last_name', 'asc');
        if ($limit)
            $query->limit($limit);
        $rows = $query->get();

        return $rows;
    }

    public static function top_celebrities_on_cause($cause_id, $limit = 10)
    {
        $sql = "SELECT c.id
            FROM celebrities_members AS cm
                INNER JOIN celebrities AS c ON c.id = cm.celebrity_id
                INNER JOIN causes_celebrities AS cc ON ( cc.celebrity_id = c.id AND cc.cause_id = $cause_id )
            GROUP BY c.id
            ORDER BY COUNT(*) DESC 
            LIMIT $limit
        ";
        
        $rows = DB::query($sql)->execute();

        $ids_arr = array();
        foreach ($rows as $row)
            $ids_arr[] = $row['id'];

        return self::find()->where('id', 'IN', DB::expr('('.implode(',', $ids_arr).')'))->get();
    }

    public static function get_celebrities_on_cause($cause_id)
    {
        $sql = "SELECT celebrity_id
            FROM causes_celebrities
            WHERE cause_id = $cause_id
        ";

        $query = self::find()
            ->where('id', 'IN', DB::expr("($sql)"))
            ->order_by('first_name', 'asc')
            ->order_by('last_name', 'asc');

        return $query->get();
    }

    public static function top_celebrities_on_field($field_id, $limit = 10)
    {
        $sql = "SELECT c.id
            FROM celebrities_members AS cm
                INNER JOIN celebrities AS c ON c.id = cm.celebrity_id
                INNER JOIN celebrities_professions AS cp ON ( cp.celebrity_id = c.id AND cp.profession_id = $field_id )
            GROUP BY c.id
            ORDER BY COUNT(*) DESC 
            LIMIT $limit
        ";
        
        $rows = DB::query($sql)->execute();

        $ids_arr = array();
        foreach ($rows as $row)
            $ids_arr[] = $row['id'];

        return self::find()->where('id', 'IN', DB::expr('('.implode(',', $ids_arr).')'))->get();
    }

    public static function get_celebrities_on_field($field_id)
    {
        $sql = "SELECT celebrity_id
            FROM celebrities_professions
            WHERE profession_id = $field_id
        ";

        $query = self::find()
            ->where('id', 'IN', DB::expr("($sql)"))
            ->order_by('first_name', 'asc')
            ->order_by('last_name', 'asc');

        return $query->get();
    }
}

// eof
