<?php

class Controller_Members_Registration extends Controller_Base
{
    public function action_index() { $this->action_register(); }

    private function _new($member, $address, $cust_user)
    {
        $member->addresses[] = $address;
        $user = new Model_User($cust_user);
        $user->member = $member;
        return $user->save();
    }

    public function action_email_clicked($hash = null)
    {
        if (is_null($hash)) return $this->action_404();

        $this->require_user('_ANYBODY_');   
        
        $member = Model_Member::query()->where('status', $hash)->get_one();

        if ($member)
        {
            $member->status = '1';

            if ($member->save())
            {
                $story_one = "Your membership is now active.";
                $this->template->flash_success = $story_one;
                $this->template->title = 'Congratulations! Thank you for confirming your membership.';
            }
            else
            {
                $story_one = "Something went wrong...";
                $this->template->flash_error = $story_one;
                $this->template->title = 'Please be patient and confirm your membership some other time.';
            }
        }
        else
        {
            $story_one = "Invalid confirmation link.";
            $this->template->flash_error = $story_one;
            $this->template->title = 'That was an invalid confimation link.';
        }

        $this->reddo('content/message.twig', array(
            'story_one' => $story_one,
            'story_two' => null,
            'link_uri' => '/',
            'link_text' => 'View home page',
        ));
    }

    public static function resend_register_confirm_email($email, $name, $hash)
    {
        $subject = 'Your registration needs confirmation, ' . self::APP_NAME;

        $confirmation_url = "http://".$_SERVER['HTTP_HOST']."/members/registration/email_clicked/$hash";
        $link = "<a href='$confirmation_url'>confirmation link</a>";

        $message = "<em>Dear $name,</em>
        <p>Congratulations, please click on this $link to complete the registration process.</p>
        <p>If you tried to click on the link above but nothing happens, just copy the link below and paste it to your browser.</p>
        <p>$confirmation_url</p>
        <p>Regards,</p>
        <p>" . self::APP_NAME . "</p>";

        return Helper::send_register_confirm_email($subject, $message, $email, $name);
    }

    private function _send_register_confirm_email($email, $name, $hash, $username, $password)
    {
        $subject = 'Your registration needs confirmation, ' . self::APP_NAME;

        $confirmation_url = "http://".$_SERVER['HTTP_HOST']."/members/registration/email_clicked/$hash";
        $link = "<a href='$confirmation_url'>confirmation link</a>";

        $message = "<em>Dear $name,</em>
        <p>Congratulations, please click on this $link to complete the registration process.</p>
        <p>If you tried to click on the link above but nothing happens, just copy the link below and paste it to your browser.</p>
        <p>$confirmation_url</p>
        <p>Username: $username</p>
        <p>Password: $password</p>
        <p>Regards,</p>
        <p>" . self::APP_NAME . "</p>";

        return Helper::send_register_confirm_email($subject, $message, $email, $name);
    }

    private function _notify_admin_re_selection($member_name, $nonprofit_name)
    {
        $subject = "Attention: $member_name chooses $nonprofit_name, " . self::APP_NAME;

        $message = "<em>Dear Admin,</em>
        <p>Attention: $member_name chooses $nonprofit_name</p>
        <p>Signin to <a href='http://" . $_SERVER['HTTP_HOST'] . "/admin'>Admin Web App</a> to send email to selected nonprofits.</p>
        <p>Regards,</p>
        <p>" . self::APP_NAME . "</p>";

        $recipients = Model_User::get_superuser_emails();

        foreach ($recipients as $recipient)
            Helper::send_email($subject, $message, $recipient['email'], $recipient['first_name'] . ' ' . $recipient['last_name']);
    }

    public function action_register()
    {
        $this->require_user('_ANYBODY_');   

        $validator = (object) array(
            'user' => Model_User::validate(),
            'password' => Model_User::validate_password(),
            'member_address' => Model_Member_Address::validate(),
            'nprofit_1' => Model_Nonprofit::validate('1'),
            'nprofit_2' => Model_Nonprofit::validate('2'),
            'nprofit_address_1' => Model_Nonprofit_Address::validate('1'),
            'nprofit_address_2' => Model_Nonprofit_Address::validate('2'),
            'referer' => Model_Member_Referer::validate(),
        );

        $ok_to_save = false;
        $is_nprofit_name_1_ok = false;
        $is_nprofit_name_2_ok = false;
        $is_nprofit_address_1_ok = false;
        $is_nprofit_address_2_ok = false;
        $referer_is_ok = false;

        if (Input::method() == 'POST')
        {
            Helper::exit_on_honeypot_captcha();

            $v1 = $validator->user->run();
            $v2 = $validator->password->run();
            $v3 = $validator->member_address->run();

            $ok_to_save = ($v1 && $v2 && $v3);

            if ( ! Input::post('terms_agreed'))
            {
                $this->errmsg['terms_agreed'] = "To proceed, please signify your agreement by checking the check box above.";
                $ok_to_save = false;
            }

            if ($ok_to_save)
            {
                $is_nprofit_name_1_ok = $validator->nprofit_1->run();
                $is_nprofit_name_2_ok = $validator->nprofit_2->run();
                $is_nprofit_address_1_ok = $validator->nprofit_address_1->run();
                $is_nprofit_address_2_ok = $validator->nprofit_address_2->run();
                $referer_is_ok = $validator->referer->run();
            }
            else
            {
                /**
                 * Validation error messages
                 */
                $this->errmsg['email'] = $validator->user->error('email');
                $this->errmsg['alias'] = $validator->user->error('alias');
                $this->errmsg['first_name'] = $validator->user->error('first_name');
                $this->errmsg['last_name'] = $validator->user->error('last_name');
                $this->errmsg['gender'] = $validator->user->error('gender');

                // $this->errmsg['birth_date'] = $validator->user->error('birth_date');
                $this->errmsg['birth_date_day'] = $validator->user->error('birth_date_day');
                $this->errmsg['birth_date_month'] = $validator->user->error('birth_date_month');

                $this->errmsg['country'] = $validator->member_address->error('country');
                $this->errmsg['state'] = $validator->member_address->error('state');
                $this->errmsg['city'] = $validator->member_address->error('city');

                $this->errmsg['password'] = $validator->password->error('password');
                $this->errmsg['confirm_password'] = $validator->password->error('confirm_password');

                /**
                 * Date for sticky form
                 */
                $this->value['email'] = $validator->user->validated('email');
                $this->value['alias'] = $validator->user->validated('alias');
                $this->value['first_name'] = $validator->user->validated('first_name');
                $this->value['last_name'] = $validator->user->validated('last_name');
                $this->value['gender'] = Input::post('gender');

                // $this->value['birth_date'] = $validator->user->validated('birth_date');
                $this->value['birth_date_day'] = Input::post('birth_date_day');
                $this->value['birth_date_month'] = Input::post('birth_date_month');
                $this->value['birth_date_year'] = Input::post('birth_date_year');

                $this->value['country'] = $validator->member_address->validated('country');
                $this->value['state'] = $validator->member_address->validated('state');
                $this->value['city'] = $validator->member_address->validated('city');
                $this->value['password'] = $validator->password->validated('password');
                $this->value['confirm_password'] = $validator->password->validated('confirm_password');

                $this->value['nprofit_country_1'] = Input::post('nprofit_country_1');
                $this->value['nprofit_name_1'] = Input::post('nprofit_name_1');
                $this->value['nprofit_city_1'] = Input::post('nprofit_city_1');
                $this->value['nprofit_state_1'] = Input::post('nprofit_state_1');

                $this->value['nprofit_country_2'] = Input::post('nprofit_country_2');
                $this->value['nprofit_name_2'] = Input::post('nprofit_name_2');
                $this->value['nprofit_city_2'] = Input::post('nprofit_city_2');
                $this->value['nprofit_state_2'] = Input::post('nprofit_state_2');

                $this->value['referer_first_name'] = Input::post('referer_first_name');
                $this->value['referer_last_name'] = Input::post('referer_last_name');
            }
        }
        else
        {
            /**
             * Initialize form
             */
            $this->value['email'] = '';
            $this->value['alias'] = ''; $this->value['first_name'] = ''; $this->value['last_name'] = '';
            $this->value['gender'] = ''; 
            // $this->value['birth_date'] = '';
            $this->value['country'] = ''; $this->value['state'] = ''; $this->value['city'] = '';
            $this->value['password'] = ''; $this->value['confirm_password'] = '';
            $this->value['nprofit_country_1'] = ''; $this->value['nprofit_name_1'] = ''; $this->value['nprofit_city_1'] = ''; $this->value['nprofit_state_1'] = '';
            $this->value['nprofit_country_2'] = ''; $this->value['nprofit_name_2'] = ''; $this->value['nprofit_city_2'] = ''; $this->value['nprofit_state_2'] = '';
            $this->value['referer_first_name'] = ''; $this->value['referer_last_name'] = '';
        }

        if ($ok_to_save)
        {
            if ($referer_is_ok)
            {
                $referer = new Model_Member_Referer(array('first_name' => Input::post('referer_first_name'), 'last_name' => Input::post('referer_last_name'), ));
                $referer->save();
            }

            // $username = Model_User::generate_unique_username(Input::post('first_name') . Input::post('last_name'));
            $username = Input::post('alias');

            $user_data = array(
                'username' => $username,
                'password' => Auth::instance()->hash_password(Input::post('password')),
                'group' => self::USER_MEMBER,
                'email' => strtolower(Input::post('email')),
                'first_name' => Input::post('first_name'),
                'last_name' => Input::post('last_name'),
                'alias' => Input::post('alias'),
                'gender' => (Input::post('gender') ? Input::post('gender') : ""),
            );

            if (Input::post('birth_date_year'))
                $user_data['birth_date'] = Input::post('birth_date_year') . "-" . Input::post('birth_date_month') . "-" . Input::post('birth_date_day');
            else
                $user_data['birth_mm_dd'] = Input::post('birth_date_month') . "/" . Input::post('birth_date_day');

            $user = new Model_User($user_data);

            $hash = md5(self::APP_SALT . time());

            $user->member = new Model_Member(array(
                'status' => $hash,
                'running_points' => 0,
                'member_referer_id' => ($referer_is_ok ? $referer->id : null),
            ));

            $user->member->address = new Model_Member_Address(array(
                'country' => Input::post('country'),
                'state' => Input::post('state'),
                'city' => Input::post('city'),
            ));

            if ($is_nprofit_name_1_ok)
            {
                $user->member->nonprofits[] = $h1 = Model_Nonprofit::get_model_autosave(Input::post('nprofit_name_1'));
                $user->member->nonprofit_selections[] = new Model_Member_Nonprofit_Selections(array('nonprofit_id' => $h1->id));

                $s = false;

                if ( ! $h1->is_contacted) // null or false
                {
                    $this->_notify_admin_re_selection(Input::post('first_name') . ' ' . Input::post('last_name'), $h1->name);

                    if (is_null($h1->is_contacted))
                    {
                        /**
                         * Set it to false which means this nonprofit was selected and needs to be contacted
                         */
                        $h1->is_contacted = false;
                        $s = true;
                    }
                }

                if ($is_nprofit_address_1_ok)
                {
                    $h1->address = new Model_Nonprofit_Address(array('city' => Input::post('nprofit_city_1'), 'state' => Input::post('nprofit_state_1'), 'country' => Input::post('nprofit_country_1'), ));
                    $s = true;
                }

                if ($s)
                    $h1->save();
            }

            if ($is_nprofit_name_2_ok)
            {
                $user->member->nonprofits[] = $h2 = Model_Nonprofit::get_model_autosave(Input::post('nprofit_name_2'));
                $user->member->nonprofit_selections[] = new Model_Member_Nonprofit_Selections(array('nonprofit_id' => $h2->id));

                $s = false;
                
                if ( ! $h2->is_contacted) // null or false
                {
                    $this->_notify_admin_re_selection(Input::post('first_name') . ' ' . Input::post('last_name'), $h2->name);

                    if (is_null($h2->is_contacted))
                    {
                        /**
                         * Set it to false which means this nonprofit was selected and needs to be contacted
                         */
                        $h2->is_contacted = false;
                        $s = true;
                    }
                }

                if ($is_nprofit_address_2_ok)
                {
                    $h2->address = new Model_Nonprofit_Address(array('city' => Input::post('nprofit_city_2'), 'state' => Input::post('nprofit_state_2'), 'country' => Input::post('nprofit_country_2'), ));
                    $s = true;
                }

                if ($s)
                    $h2->save();
            }

            if ($user->save())
            {
                $result = $this->_send_register_confirm_email(Input::post('email'), Input::post('first_name') . ' ' . Input::post('last_name'), $hash, $username, Input::post('password') );

                if ($result)
                    Session::set_flash('success', 'Congratulations, please your check email and click on the confirmation link to complete the registration process.');
                else
                    Session::set_flash('error', 'Registration initiated but sending of confirmation email was not successful. '.self::APP_CONTACT_ADMIN);

                Response::redirect('members/registration/for_confirmation');
            }

            $this->template->flash_error = 'Registration was not successful.  Please try again later.';
        }

        $this->template->scripts[] = 'jquery.maskedinput-1.3.min.js';
        $this->template->title = "Membership Form";
        
        $this->reddo('content/registration.twig', array(
            'countries_with_grouping' => Model_Static::get_countries_with_grouping(),
            'app_server_ip' => Controller_System::ip(),
            'nonprofit_arr' => Model_Nonprofit::get_nonprofit_arr(),
        ));
    }

    public function action_for_confirmation()
    {
        $this->require_user('_ANYBODY_');   

        $story_one = "Please check your email and click on the confirmation link.";
        $story_two = "";

        $this->template->title = 'One more click and registration is complete!';

        $this->reddo('content/message.twig', array(
            'story_one' => $story_one,
            'story_two' => $story_two,
            'link_uri' => '/',
            'link_text' => 'Go to home page',
        ));
    }
}
