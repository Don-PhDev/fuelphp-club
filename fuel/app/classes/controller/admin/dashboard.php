<?php

class Controller_Admin_Dashboard extends Controller_Admin_Base
{
    public function action_index()
    {
        $this->template->cdn_scripts[] = '/assets/js/spin.min.js';
        $this->template->cdn_scripts[] = '/assets/js/jquery.spin.js';

        $this->reddo('admin/dashboard.twig', array(
            'selected_nonprofits' => Model_Nonprofit::find()->where('is_contacted', 0)->get(),
        ));
    }
}
