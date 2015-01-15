<?php

class Controller_Admin_Donor extends Controller_Admin_Base
{
    public function action_index()
    {
        $this->reddo('admin/donor_list.twig', array(
            'donors' => Model_Donor::find()->get(),
        ));
    }

    public function action_create()
    {
        $val_donor = Model_Donor::validate('donor');

        if (Input::method() == "POST")
        {
            if ($val_donor->run())
            {
                $donor = new Model_Donor();
                $donor->name = $val_donor->validated('name');
                $donor->organization_name = $val_donor->validated('organization_name');
                $donor->organization_text = $val_donor->validated('about');

                $image_filenames = Helper::upload_multiple_files('donor', false);

                if (is_array($image_filenames))
                {
                    if (isset($image_filenames[0]))
                        $donor->image_filename = $image_filenames[0]['saved_as'];
                    else
                        $donor->image_filename = "";

                    if (isset($image_filenames[1]))
                        $donor->organization_logo = $image_filenames[1]['saved_as'];
                    else
                        $donor->organization_logo = "";
                }

                if ($donor->save())
                {
                    Session::set_flash('success', "Donor was successfully saved!");
                    Response::redirect('admin/donor');
                }

                $this->template->flash_error = "Failed attempt to save donor".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                /**
                 * Sticky
                 */
                $this->value['name'] = Input::post('name');
                $this->value['about'] = Input::post('about');
                $this->value['organization_name'] = Input::post('organization_name');

                /**
                 * Validation error messages
                 */
                $this->errmsg['name'] = $val_donor->error('name');
                $this->errmsg['about'] = $val_donor->error('about');
                $this->errmsg['organization_name'] = $val_donor->error('organization_name');
            }
        }

        $this->reddo('admin/donor_create.twig');
    }

    public function action_endow($id = null)
    {
        if (is_null($id)) return $this->action_404();

        $val_donation = Model_Donor::validate_donation('donor');

        if (Input::method() == "POST")
        {
            if ($val_donation->run())
            {
                if (Input::post('recipient_id'))
                    $recipient_id = Input::post('recipient_id');
                else
                    $recipient_id = Model_Nonprofit::get_id_autosave(Input::post('recipient'));

                $donor = Model_Donor::find($id);
                $donor->nonprofits[$recipient_id] = Model_Nonprofit::find($recipient_id);
                $donor->donations[] = Model_Donors_Nonprofits_Donation::forge(array(
                    'nonprofit_id' => $recipient_id,
                    'amount' => $val_donation->validated('amount'),
                ));

                if ($donor->save())
                {
                    Session::set_flash('success', "Donation was successfully saved!");
                    Response::redirect('admin/donor');
                }

                $this->template->flash_error = "Failed attempt to save donation".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                /**
                 * Sticky
                 */
                $this->value['recipient'] = Input::post('recipient');
                $this->value['amount'] = Input::post('amount');

                /**
                 * Validation error messages
                 */
                $this->errmsg['recipient'] = $val_donation->error('recipient');
                $this->errmsg['amount'] = $val_donation->error('amount');
            }
        }

        $this->reddo('admin/donor_endow.twig', array(
            'donor' => Model_Donor::find($id),
        ));
    }

}
