<?php

class Controller_Admin_Ajax_Delete extends Controller_Admin_Base
{
    public function action_celebrity()
    {
        $success = false;
        $message = null;

        $id = Input::post('id');

        if ($id)
        {
            if ($ar = Model_Celebrity::find($id))
                if ($ar->delete())
                    $success = true;
        }

        return json_encode(array('success' => $success, 'message' => $message));
    }
}

// eof
