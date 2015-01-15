<?php

namespace Fuel\Tasks;

class CauseCorrection
{
    public static function run()
    {
        $causes = \Model_Cause::query()->order_by('cause', 'asc')->get();

        foreach ($causes as $cause)
        {
            $count = count($cause->celebrities);

            if ($count == 0)
            {
                if ( ! $cause->delete())
                {
                    \Cli::write('Failed attempt to delete cause');
                    break;
                }
            }
            else if ($count == 1 || $count == 2)
            {
                $correction = \Cli::prompt($cause->id . ' = ' . $cause->cause . ' (' . $count . ') ');

                if ($correction == "quit")
                    break;

                else if ($correction == "delete")
                {
                    if ( ! $cause->delete())
                    {
                        \Cli::write('Failed attempt to delete cause');
                        break;
                    }
                }
                else if ($ar = \Model_Cause::find_by_cause($correction))
                {
                    \Cli::write($ar->id . ' = ' . $ar->cause);

                    foreach ($cause->celebrities as $celebrity)
                    {
                        unset($celebrity[$cause->id]);
                        $celebrity->causes[] = $ar;
                        if ( ! $celebrity->save())
                        {
                            \Cli::write('Failed attempt to save celebrity');
                            break;
                        }
                    }

                    if ( ! $cause->delete())
                    {
                        \Cli::write('Failed attempt to delete cause');
                        break;
                    }
                    
                    $cause->save();
                }
            }
            else
            {
                \Cli::write($cause->id . ' = ' . $cause->cause . ' (' . $count . ') -- OK --');
            }
        }
    }
}

// eof
