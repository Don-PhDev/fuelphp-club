<?php
/**
 * helper.php
 */

class Helper
{
    public static function exit_on_honeypot_captcha()
    {
        /**
         * Check honeypot captcha
         */
        if (Input::post('userComment') != "")
        {
            /**
             * This is not human entry!
             */
            echo "Thank you, have a nice day!";
            exit;
        }
    }

    public static function extract_emails($string)
    {
        $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $string, $matches);
        return $matches[0];
    }

    public static function get_avatar($user_id, $email, $size = 122)
    {
        if ($img_filename = Helper::get_image_from_glob("member/" . "webcam_" . $user_id . ".*"))
            $img_filename = '/' . $img_filename;
        else
            $img_filename = "http://www.gravatar.com/avatar/" . md5($email) . "?d=mm&amp;s=$size";

        return $img_filename;
    }

    public static function get_image_from_glob($path_wildcard)
    {
        $image = null;

        $ext_whitelist = array('jpg', 'jpeg', 'gif', 'png');
        $arr = glob( DOCROOT.DS."web_data/$path_wildcard" );

        if (count($arr) > 0)
        {
            foreach ($arr as $path_file)
            {
                $ext = pathinfo($path_file, PATHINFO_EXTENSION);
                if (in_array($ext, $ext_whitelist))
                {
                    $image = substr($path_file, strlen(DOCROOT.DS));
                    break;
                }
            }
        }

        return $image;
    }

    public static function rename_uploaded_file($path, $old_name, $new_name)
    {
        $path = DOCROOT.DS."web_data/$path/";
        return rename($path.$old_name, $path.$new_name);
    }

    public static function upload_multiple_files($path, $require_one = true)
    {
        Upload::process(array(
            'path' => DOCROOT.DS."web_data/$path/",
            'auto_rename' => true,
            'change_case' => 'lower',
            'normalize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        ));

        if ( ! Upload::is_valid())
            return false;

        Upload::save();
        
        return Upload::get_files();
    }

    public static function upload_file($path, $require_one = true)
    {
        Upload::process(array(
            'path' => DOCROOT.DS."web_data/$path/",
            'auto_rename' => true,
            'change_case' => 'lower',
            'normalize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        ));

        $filename = null;
        $str_error = null;

        if (Upload::is_valid())
        {
            Upload::save();
            $image = current(Upload::get_files());
            $filename = $image['saved_as'];
        }

        $error_array = current(Upload::get_errors());

        if ($error_array['errors'][0]['error'] == Upload::UPLOAD_ERR_NO_FILE)
        {
            if ($require_one)
                $str_error = $error_array['errors'][0]['message'];
        }
        else
        {
            $str_error = $error_array['errors'][0]['message'];
        }

        return array($filename, $str_error);
    }

    public static function extract_integer($str)
    {
        preg_match_all('!\d+!', $str, $matches);
        return (int) implode('', $matches[0]);
    }

    public static function format_phone($phone)
    {
        if ( ! is_numeric($phone))
            return $phone;

        $phone = str_pad($phone, 10, "0", STR_PAD_LEFT);
        return '(' . substr($phone, 0, 3) . ')- ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
    }

    public static function send_invitation_emails($inviter_name, $arr_emails)
    {
        $recipient_emails = array();

        $message = "<p>Hello!</p>
        <p>Join Club4Causes to support your favorite non-profit organization.</p>
        <p>See you,</p>
        <p>$inviter_name</p>
        ";

        foreach ($arr_emails as $email)
            if (self::send_email("$inviter_name invites you to join Club4Causes!", $message, $email, "Friend"))
                $recipient_emails[] = $email;

        return $recipient_emails;
    }

    public static function send_register_confirm_email($subject, $message, $email, $name)
    {
        return self::send_email($subject, $message, $email, $name);
    }

    public static function send_email($subject, $message, $email, $name)
    {
        $html_message = '<html>
        <head><title>' . $subject . '</title></head>
        <body>' . nl2br(stripslashes($message)) . '</body>
        </html>';

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= 'To: ' . $name . ' <' . $email . '>' . "\r\n";
        $headers .= 'From: ' . Controller_Base::APP_NAME . ' <' . Controller_Base::APP_EMAIL . '>' . "\r\n";
        $headers .= 'Reply-To: ' . Controller_Base::APP_EMAIL . "\r\n";
        $headers .= 'Bcc: ' . Controller_Base::PROGRAMMERS_EMAIL . "\r\n";

        // Mail it
        return mail($email, $subject, $html_message, $headers);
    }

    public static function render_flag($country)
    {
        return '<div class="flag flag-' . Model_Static::get_country_code($country) . ' country_flag"></div>';
    }
}

// eof
