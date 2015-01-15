<?php

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 * 
 * @package  app
 * @extends  Controller
 */
class Controller_Home extends Controller_Base
{
	public function action_index()
	{
        $this->template->show_header_content = true;

        $top_nprofit = Model_Nonprofit::find()->order_by('running_points', 'desc')->limit(50)->get();
        $top_members = Model_Member::find()->order_by('running_points', 'desc')->limit(15)->get();

        $this->template->styles[] = 'flag.css';

		$this->reddo('content/index.twig', array(
            'top_nprofit' => $top_nprofit,
            'top_members' => $top_members,
            'newest_members' => Model_Member::find()->order_by('id', 'desc')->limit(10)->get(),
            'birthday_users' => Model_User::generate_birthday_celebrants(),
            'comments' => Model_Topic::get_latest_comments(),
		));
	}
}
