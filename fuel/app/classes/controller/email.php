<?php

class Controller_Email extends Controller_Base
{
    public function action_index($recipient = null)
    {
        if (is_null($recipient))
            $recipient = 'raymond@philippinedev.com';

        \Package::load('email');
                
        $email = Email::forge();
        $email->from('info@dev.club4causes.com', 'Server');
        $email->to($recipient, 'The Name');
        $email->subject('This is the subject');
        $email->body('This is my message');

        try
        {
            $email->send();
        }
        catch(\EmailValidationFailedException $e)
        {
            // The validation failed
            exit('email validation failed');
        }
        catch(\EmailSendingFailedException $e)
        {
            // The driver could not send the email
            exit('email driver cannot send email');
        }
        echo 'ayos yata';
    }
}

// eof
