<?php

namespace Fuel\Tasks;

class Celeb2profession
{
    public static function run()
    {
        \Cli::write('Nothing to do.');
    }

    public static function television()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function movies()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function music()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function sports()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function theater()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function comedy()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function fashion()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function business()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function military()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function literature()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function radio()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function journalism()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function politics()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function food()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function society()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function visual_arts()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function dance()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function science()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function royalty()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function exploration()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function magic()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function health_and_medicine()
    {
        self::_push_rels(__FUNCTION__);
    }

    public static function religion_and_spirituality()
    {
        self::_push_rels(__FUNCTION__);
    }

    private static function _push_rels($field)
    {
        require "data/celebrities_for_" . strtolower($field) . ".php";
        $celeb_arr = array_unique($celebrities);

        $total_new_rel = 0;

        $query = \Model_Profession::find()->where('field', str_replace('_', ' ', $field));
        $profession = $query->get_one();
        $field_id = $profession->id;

        \Cli::write($field . ' : ' . $field_id);

        foreach ($celeb_arr as $celeb_name)
        {
            $name = explode(' ', $celeb_name, 2);

            if (count($name) == 2)
                $query = \Model_Celebrity::find()->where('first_name', $name[0])->where('last_name', $name[1])->limit(1);
            else
                $query = \Model_Celebrity::find()->where('first_name', $name[0])->limit(1);

            $celebrity = $query->get_one();

            if ($celebrity)
            {
                $profession->celebrities[] = $celebrity;

                if ($profession->save())
                {
                    \Cli::write($name[0] . ' ' . (isset($name[1]) ? $name[1] : '') . ' : ' . $field_id . ' => ' . $celebrity->id);
                    $total_new_rel++;
                }
                else
                {
                    \Cli::write("Error: failed attempt to save relationship");
                    \Cli::write($name[0] . ' ' . (isset($name[1]) ? $name[1] : '') . ' : ' . $field_id . ' => ' . $celebrity->id);
                    exit;
                }
            }
        }

        \Cli::write('');
        \Cli::write('--- summary ---');
        \Cli::write('c2profession relationship added: ' . $total_new_rel);
    }
}
