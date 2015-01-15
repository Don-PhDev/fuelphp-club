<?php

class Controller_Contact extends Controller_Base
{
    public function action_index()
    {
        if (Input::method() == "POST")
        {
            Helper::exit_on_honeypot_captcha();

            $val_message = Model_Message::validate('message');

            if ($val_message->run())
            {
                $message = new Model_Message(array(
                    'name' => Input::post('first_name'),
                    'regarding' => Input::post('regarding'),
                    'message' => Input::post('message'),
                    'email' => Input::post('email'),
                    'phone_number' => Input::post('phone_number'),
                ));

                if ($message->save())
                {
                    Helper::send_email("(".Controller_Base::APP_NAME." auto-confirm receipt) We'll get back to you ASAP!", Input::post('message'), Input::post('first_name'), Input::post('first_name'));

                    Session::set_flash('success', "We appreciate you for sending us a message.  We will get back to you in the next 24 to 48 hours.");
                    Response::redirect('/');
                }

                $this->template->flash_error = "Failed attempt to save message".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                $this->value['regarding'] = Input::post('regarding');
                $this->value['message'] = Input::post('message');
                $this->value['first_name'] = Input::post('first_name');
                $this->value['email'] = Input::post('email');
                $this->value['phone_number'] = Input::post('phone_number');

                $this->errmsg['regarding'] = $val_message->error('regarding');
                $this->errmsg['message'] = $val_message->error('message');
                $this->errmsg['first_name'] = $val_message->error('first_name');
                $this->errmsg['email'] = $val_message->error('email');
            }
        }
        else
        {
            if ($this->current_user)
            {
                $this->value['first_name'] = $this->current_user->first_name;
                $this->value['email'] = $this->current_user->email;
                $this->value['phone_number'] = $this->current_user->phone_number;
            }
        }

        $regarding = array(
            'Information' => 'Information',
            'Advertising' => 'Advertising',
            'Shopping' => 'Shopping',
            'Media' => 'Media',
            'Non Profits' => 'Non Profits',
            'Administration' => 'Administration',
            'Others' => 'Others',
        );

        $this->reddo('content/contact.twig', array(
            'regarding' => $regarding,
        ));
    }
}
