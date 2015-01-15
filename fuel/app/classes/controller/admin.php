<?php

class Controller_Admin extends Controller_Base
{
    public function action_index()
    {
        Response::redirect('admin/dashboard');
    }
}
