<?php

class Controller_Admin_Iemail extends Controller_Admin_Base
{
    public function action_index()
    {
        $this->reddo('admin/info_email_list.twig', array(
            'emails' => Model_Infoemail::find('all'),
        ));
    }

    public function action_create()
    {
        $val_iemail = Model_Infoemail::validate();

        if (Input::method() == "POST")
        {
            if ($val_iemail->run())
            {
                $iemail = new Model_Infoemail();
                $iemail->email_type = 'DAILY_EMAIL';
                $iemail->email_subject = $val_iemail->validated('subject');
                $iemail->email_text = $val_iemail->validated('email_text');
                $iemail->delivery_schedule = '1111111'; // 7 bits one for each day of the week (0 = no action, 1 = send)
                $iemail->is_active = 1;

                if ($iemail->save())
                {
                    Session::set_flash('success', "Email was successfully saved!");
                    Response::redirect('admin/iemail');
                }

                $this->template->flash_error = "Failed attempt to create email".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                /**
                 * Sticky
                 */
                $this->value['subject'] = Input::post('subject');
                $this->value['email_text'] = Input::post('email_text');

                /**
                 * Validation error messages
                 */
                $this->errmsg['subject'] = $val_iemail->error('subject');
                $this->errmsg['email_text'] = $val_iemail->error('email_text');
            }
        }

        $this->reddo('admin/info_email_create.twig');
    }
}
