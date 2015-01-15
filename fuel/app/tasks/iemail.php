<?php
/**
 * iemail.php
 * @date 29 January 2013
 * @author Raymond S. Usbal
 */

namespace Fuel\Tasks;

class Iemail
{
    public static function run()
    {
        \Cli::write('Nothing to do.');
    }

    public static function subscribe()
    {
        \Cli::write('...');

        $eids = array();
        $oeids = \DB::query('SELECT id FROM infoemails WHERE is_active = 1')->execute();
        foreach ($oeids as $eid)
            $eids[] = $eid;

        $members = \Model_Member::find('all');

        foreach ($members as $member)
        {
            \Cli::write($member->user->first_name . " " . $member->user->last_name);

            foreach ($eids as $eid)
            {
                \Cli::write("\t" . $eid['id']);
                $member->infomails[] = \Model_Infoemail::find($eid);
            }

            if ($member->save())
                \Cli::write("   *** success ***");
            else
            {
                \Cli::write("   *** error ***");
                exit;
            }
        }
    }

    public static function send()
    {
        \Package::load('email');

        $members = \Model_Member::find('all');

        foreach ($members as $member)
        {
            if ($member->user->group != 1)
                continue;

            $full_name = $member->user->first_name . " " . $member->user->last_name;

            \Cli::write($full_name);

            foreach ($member->infomails as $infoemail)
            {
                /*
                $email = \Email::forge();
                $email->from('info@dev.club4causes.com', 'Information');
                $email->to($member->user->email, $full_name);
                $email->subject($infoemail->email_subject);
                $email->body($infoemail->email_text);

                try { $email->send(); }

                catch(\EmailValidationFailedException $e)
                {
                    \Cli::write("\t Email validation failed");
                    exit;
                }

                catch(\EmailSendingFailedException $e)
                {
                    \Cli::write("\t Email driver cannot send email");
                    exit;
                }
                */

                #Iemail::send_email($infoemail->email_subject, $infoemail->email_text, $member->user->email, $full_name);

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Additional headers
                $headers .= 'To: ' . $full_name . ' <' . $member->user->email . '>' . "\r\n";
                $headers .= 'From: Club4causes Web Application <info@dev.club4causes.com>' . "\r\n";
                $headers .= 'Reply-To: raymond@philippinedev.com' . "\r\n";

                // Mail it
                mail($member->user->email, $infoemail->email_subject, $infoemail->email_text, $headers);

                \Cli::write("\t" . $infoemail->email_subject . " (SUCCESS)");
            }
        }
    }
}
