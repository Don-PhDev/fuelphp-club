<?php

class Controller_Ajax_Loader extends Controller_Base
{
    public function action_cause_links()
    {
        $data['causes'] = Model_Cause::query()->order_by('cause', 'asc')->get();
        return View::forge('content/cause-div-links.twig', $data);
    }

    public function action_field_links()
    {
        $data['fields'] = Model_Profession::query()->order_by('field', 'asc')->get();
        return View::forge('content/field-div-links.twig', $data);
    }

    public function action_celebrities_links()
    {
        $data['celebrities'] = Model_Celebrity::get_celebrities_on_letters(Input::get('query', null));
        return View::forge('content/celebrity-links.twig', $data);
    }

    public function action_count_celeb_for_cause($id = null)
    {
        $success = false;
        $count = null;

        if ( ! is_null($id))
        {
            if ($a = Model_Cause::find($id))
            {
                $success = true;
                $count = count($a->celebrities);
            }
        }

        header('Content-Type: application/json');
        return json_encode(array('success' => $success, 'count' => $count));
    }

    public function action_count_celeb_for_field($id = null)
    {
        $success = false;
        $count = null;

        if ( ! is_null($id))
        {
            if ($a = Model_Profession::find($id))
            {
                $success = true;
                $count = count($a->celebrities);
            }
        }

        header('Content-Type: application/json');
        return json_encode(array('success' => $success, 'count' => $count));
    }
}

// eof
