<?php

class Controller_Admin_Signin extends Controller_Admin_Base
{
    public function action_index()
    {
        $this->reddo('admin/signin.twig');
    }
}
