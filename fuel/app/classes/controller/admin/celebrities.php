<?php

class Controller_Admin_Celebrities extends Controller_Admin_Base
{
    public function action_index()
    {
        $this->reddo('admin/celebrities.twig', array(
            'celebrities' => DB::query('SELECT id, first_name, last_name FROM celebrities ORDER BY 2, 3')->execute()->as_array(),
        ));
    }

    public function action_create()
    {
        if (Input::method() == 'POST')
        {
            $val_celeb = Model_Celebrity::validate('celeb');

            list($image_filename, $upload_error) = Helper::upload_file('celebrities/img', false);

            if ($val_celeb->run() && $image_filename)
            {
                $celeb = new Model_Celebrity();

                $name_arr = explode(' ', Input::post('name'), 2);
                $celeb->image_filename = $image_filename;
                $celeb->first_name = $name_arr[0];
                if ($name_arr[1])
                    $celeb->last_name = $name_arr[1];

                foreach (Input::post('nonprofit') as $a)
                    if ($a = trim($a))
                        if ($b = Model_Nonprofit::get_model_autosave($a))
                            $celeb->nonprofits[] = $b;

                foreach (Input::post('cause') as $a)
                    if ($a = trim($a))
                        if ($b = Model_Cause::get_model_autosave($a))
                            $celeb->causes[] = $b;

                foreach (Input::post('field') as $a)
                    if ($a = trim($a))
                        if ($b = Model_Profession::get_model_autosave($a))
                            $celeb->professions[] = $b;

                if ($celeb->save())
                {
                    Session::set_flash('success', "Celebrity was successfully saved!");
                    Response::redirect('admin/celebrities');
                }

                $this->template->flash_error = "Failed attempt to save celebrity".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                $this->value['name'] = Input::post('name');

                $this->errmsg['name'] = $val_celeb->error('name');
                if (!$image_filename)
                    $this->errmsg['image_filename'] = "The field Picture is required and must contain a value.";
            }
        }

        $this->reddo('admin/celebrity_form.twig', array(
            'nonprofit_arr' => Model_Nonprofit::get_nonprofit_arr(),
            'cause_arr' => DB::query('SELECT cause FROM causes ORDER BY 1')->execute()->as_array(),
            'field_arr' => DB::query('SELECT field FROM professions ORDER BY 1')->execute()->as_array(),
        ));
    }
}
