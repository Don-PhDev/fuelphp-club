<?php

class Controller_Admin_System extends Controller_Admin_Base
{
    /**
     * The 404 action for the application.
     * 
     * @access  public
     * @return  Response
     *
    public static function action_404()
    {
        $title = 'Page not found!';
        $message = 'Please check URL, cannot generate requested page.';
        $this->template->title = '404 &raquo; '.$title;
        $this->template->content = View::forge('admin/404.twig', array('title' => $title, 'message' => $message), 404);
    }
    */
}