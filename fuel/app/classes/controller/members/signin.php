<?php

class Controller_Members_Signin extends Controller_Base
{
    public function action_index()
    {
        $this->reddo(C4c::THEME.'/1026.site_header_login.twig');
    }
}
