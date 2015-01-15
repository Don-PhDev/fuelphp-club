<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;

/**
 * Robot example task
 *
 * Ruthlessly stolen from the beareded Canadian sexy symbol:
 *
 *      Derek Allard: http://derekallard.com/
 *
 * @package     Fuel
 * @version     1.0
 * @author      Phil Sturgeon
 */

class Points
{
    public static function run()
    {
        \Cli::write('Nothing to do.');
    }

    public static function add_random_points()
    {
        \Cli::write('...');

        $members = \Model_Member::find('all');

        foreach ($members as $member)
        {
            $action = rand(0, 1);
            $addpts = rand(1, 10000);

            $addpts += ($addpts & 1) ? 1 : 0; // add one if odd

            if ($action == 0)
                \Cli::write($member->user->first_name . ' ' . $member->user->last_name . ' : PASS');
            else
            {
                \Cli::write($member->user->first_name . ' ' . $member->user->last_name . ' : ' . $addpts);

                $member->running_points += $addpts;
                $member->points[] = new \Model_Member_Points(array(
                    'points' => $addpts,
                    'source' => 'System Points',
                ));

                foreach ($member->nonprofits as $idx => $mem_nprofit)
                {
                    \Cli::write("\t" . $mem_nprofit->name . ' : ' . ($addpts / 2));

                    $member->nonprofits[$idx]->running_points += ($addpts / 2);
                    $member->nonprofits[$idx]->points[] = new \Model_Nonprofit_Points(array(
                        'points' => $addpts,
                        'source' => 'System Points',
                    ));
                }

                if ( ! $member->save())
                {
                    \Cli::write('Attempt to save unsuccessful.');
                    exit;
                }
            }
        }
    }
}
