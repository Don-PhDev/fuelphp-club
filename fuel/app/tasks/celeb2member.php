<?php

namespace Fuel\Tasks;

class Celeb2member
{
    public static function run()
    {
        \Cli::write('Nothing to do.');
    }

    public static function add_random_rel()
    {
        \Cli::write('Starts...');

        $celebrities = \Model_Celebrity::find('all');

        $members = \Model_Member::find('all');
        $mem_tot = count($members);

        $total_celeb = 0;
        $total_celeb_proc = 0;
        $total_new_rel = 0;

        foreach ($celebrities as $celebrity)
        {
            $total_celeb++;

            if ($n = rand(0, $mem_tot))
            {
                $ptr_arr = (array) array_rand($members, $n);

                if (count($ptr_arr) > 0)
                {
                    $total_celeb_proc++;

                    foreach ($ptr_arr as $key)
                    {
                        $celebrity->members[] = $members[$key];
                        if ( ! $celebrity->save())
                            \Cli::write("Abort: Failed attempt to save celebrity: " . $celebrity->id . " to member: " . $members[$key]->id);

                        $total_new_rel++;
                    }
                }
            }
        }

        \Cli::write('Celebrity: ' . $total_celeb);
        \Cli::write('Celebrity relationship added: ' . $total_celeb_proc);
        \Cli::write('c2member relationship added: ' . $total_new_rel);
    }
}
