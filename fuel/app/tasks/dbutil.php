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

class Dbutil
{
    public static function run()
    {
        \Cli::write('...');

        $result = \DB::query("SELECT table_name, table_rows
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = 'clubcaus_c4c'"
        )->execute();
        
        $n = 0;

        foreach ($result as $row)
            if ('migration' != $row['table_name'])
                \Cli::write($row['table_name'] . ' : ' . $row['table_rows']);
    }

    public static function truncate()
    {
        \Cli::write('...');

        $result = \DB::query("SELECT table_name, ENGINE
            FROM information_schema.tables
            WHERE table_type = 'BASE TABLE'
            AND table_schema = 'clubcaus_c4c'
            ORDER BY table_name ASC"
        )->execute();
        
        $n = 0;

        foreach ($result as $row)
        {
            if ('migration' != $row['table_name'])
            {
                \DB::query("TRUNCATE `" . $row['table_name'] . "`")->execute();
                \Cli::write('Truncating ' . $row['table_name']);
                $n++;
            }
        }

        \Cli::write('Tables updated: ' . $n);

        Dbutil::run();
    }
}
