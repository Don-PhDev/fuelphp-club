<?php

class Controller_My extends Controller_Base
{
    public function action_index()
    {
        $this->action_homepage();
    }

    public function action_nonprofit($nonprofit_id = null)
    {
        if (is_null($nonprofit_id)) return $this->action_404();

        $member = $this->require_auth_get_member();

        if (Input::method() == "POST")
        {
            $val_nprofit = Model_Nonprofit::validate('nonprofit');
            $val_nprofit_address = Model_Nonprofit_Address::validate('addr');

            if ($val_nprofit->run())
            {
                /**
                 * This is update procedure
                 */

                $save_nonprofit_id = Model_Nonprofit::get_id_autosave(Input::post('nprofit_name'));
                $nprofit = Model_Nonprofit::find($save_nonprofit_id);

                if ($val_nprofit_address->run())
                {
                    $nprofit->address = new Model_Nonprofit_Address(array(
                        'city' => Input::post('nprofit_city'),
                        'state' => Input::post('nprofit_state'),
                        'country' => Input::post('nprofit_country'),
                    ));
                }

                /**
                 * Add nonprofit to member
                 */
                $member->nonprofits[] = $nprofit;

                /**
                 * Delete older one to complete the change of nonprofit
                 */
                unset($member->nonprofits[$nonprofit_id]);

                if ($member->save())
                {
                    Session::set_flash('success', 'Your choice of non profit has been successfully updated.');
                    Response::redirect('/my/homepage');
                }

                $this->template->flash_error = "Failed attempt to update non profit".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                $this->errmsg['nprofit_name'] = $val_nprofit->error('nprofit_name');
            }
        }
        else
        {
            $nonprofit = Model_Nonprofit::find($nonprofit_id);

            $this->value['nprofit_name'] = $nonprofit->name;

            if ($nonprofit->address)
            {
                $this->value['nprofit_country'] = $nonprofit->address->country;
                $this->value['nprofit_city'] = $nonprofit->address->city;
                $this->value['nprofit_state'] = $nonprofit->address->state;
            }
        }

        $this->template->cdn_scripts[] = "/assets/js/american_states_cities.js";

        $this->reddo('content/member-update-nonprofit.twig', array(
            'show_home_btn_on_right' => true,
            'img_filename' => Helper::get_avatar($member->user->id, $member->user->email),
            'member' => $member,
            'countries_with_grouping' => Model_Static::get_countries_with_grouping(),
            'nonprofit_arr' => Model_Nonprofit::get_nonprofit_arr(),
        ));
    }

    public function action_homepage()
    {
        $this->require_auth();

        $user_id = Session::get('user_id');

        $member = Model_Member::query()->where(array('user_id', $user_id))->get_one();

        if (Input::method() == "POST")
        {
            $arr_emails = Helper::extract_emails(Input::post('invite_emails'));

            if (count($arr_emails) > 0)
            {
                $recipient_emails = Helper::send_invitation_emails($member->user->first_name." ".$member->user->last_name, $arr_emails);
                $csv_emails = implode(', ', $recipient_emails);
                $this->template->flash_success = "You have successfully invited $csv_emails";
            }

            /**
             * Upload image filename
             */
            list($image_filename, $upload_error) = Helper::upload_file('member', false);

            if ($image_filename && is_null($upload_error))
            {
                $result = Helper::rename_uploaded_file(
                    'member',
                    $image_filename,
                    "webcam_" . $user_id . "." . pathinfo($image_filename, PATHINFO_EXTENSION)
                );

                if ( ! $result)
                    $this->template->flash_error = "Failed attempt to finish assigning of image (rename)".self::THIS_SHOULD_NOT_HAPPEN;
            }
        }

        /**
         * Javascript for webcam
         */
        $this->template->cdn_scripts[] = "//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js";
        $this->template->cdn_scripts[] = "/assets/vendor/scriptcam/scriptcam.js";

        $this->reddo('content/member-homepage.twig', array(
            'show_home_btn_on_right' => true,
            'img_filename' => Helper::get_avatar($member->user->id, $member->user->email),
            'member' => $member,
            'reasons' => Model_Member_Nonprofit_Selections::get_nonprofit_selections($member->id),
        ));
    }
}

// eof
