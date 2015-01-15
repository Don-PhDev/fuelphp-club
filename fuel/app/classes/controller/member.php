<?php

class Controller_Member extends Controller_Base
{
    public function action_index($id = null)
    {
        if (is_null($id)) return $this->action_404();

        $logged_user = Model_User::find(Session::get('user_id'));

        if (_LOGGED_USER_ && $id == $logged_user->member->id)
            Response::redirect('/my/homepage');

        $member = Model_Member::find($id);
        if (is_null($member)) return $this->action_404();

        if (Input::method() == "POST")
        {
            $val_comment = Model_Member_Comment::validate('comment');

            if ($val_comment->run())
            {
                $member->received_comments[] = new Model_Member_Comment(array(
                    'commenting_member_id' => $logged_user->member->id,
                    'comment' => $val_comment->validated('comment'),
                ));

                if ($member->save())
                    $this->template->flash_success = "Your comments was successfully saved!";
                else
                    $this->template->flash_error = "Failed attempt to save comments".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                $this->errmsg['comment'] = $val_comment->error('comment');
                $this->value['comment'] = Input::post('comment');
            }
        }

        $this->reddo('content/member.twig', array(
            'show_home_btn_on_right' => true,
            'img_filename' => Helper::get_avatar($member->user->id, $member->user->email),
            'member' => $member,
            'reasons' => Model_Member_Nonprofit_Selections::get_nonprofit_selections($id),
        ));
    }
}

// eof
