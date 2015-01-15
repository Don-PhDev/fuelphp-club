<?php

class Controller_Admin_Benefactor extends Controller_Admin_Base
{
    public function action_index()
    {
        $this->reddo('admin/benefactor_list.twig', array(
            'benefactors' => Model_Benefactor::find()->get(),
        ));
    }

    public function action_create()
    {
        $val_benefactor = Model_Benefactor::validate('benefactor');

        if (Input::method() == "POST")
        {
            if ($val_benefactor->run())
            {
                $ben = new Model_Benefactor();
                $ben->name = Input::post('name');
                $ben->organization_name = Input::post('organization_name');
                $ben->organization_text = Input::post('about');

                $image_filenames = Helper::upload_multiple_files('benefactor', false);

                if (is_array($image_filenames))
                {
                    if (isset($image_filenames[0]))
                        $ben->image_filename = $image_filenames[0]['saved_as'];
                    else
                        $ben->image_filename = "";

                    if (isset($image_filenames[1]))
                        $ben->organization_logo = $image_filenames[1]['saved_as'];
                    else
                        $ben->organization_logo = "";
                }

                if ($ben->save())
                {
                    Session::set_flash('success', "Benefactor was successfully saved!");
                    Response::redirect('admin/benefactor');
                }

                $this->template->flash_error = "Failed attempt to save benefactor".self::THIS_SHOULD_NOT_HAPPEN;
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
                $this->errmsg['name'] = $val_benefactor->error('name');
                $this->errmsg['about'] = $val_benefactor->error('about');
                $this->errmsg['organization_name'] = $val_benefactor->error('organization_name');
            }
        }

        $this->reddo('admin/benefactor_create.twig');
    }
}
