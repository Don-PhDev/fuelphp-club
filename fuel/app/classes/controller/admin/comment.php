<?php

class Controller_Admin_Comment extends Controller_Admin_Base
{
    public function action_index()
    {
        $comments = array();
        $val_comment = Model_Comment::validate('comment');

        if (Input::method() == "POST")
        {
            if ($val_comment->run())
            {
                $c1 = Model_Topic::find(1)->comments[] = new Model_Comment(array(
                    'topic_id' => 1, 
                    'title' => Input::post('title_members'),
                    'message' => Input::post('members'),
                ));
                $c2 = Model_Topic::find(2)->comments[] = new Model_Comment(array(
                    'topic_id' => 2, 
                    'title' => Input::post('title_nonprofits'),
                    'message' => Input::post('nonprofits'),
                ));
                $c3 = Model_Topic::find(3)->comments[] = new Model_Comment(array(
                    'topic_id' => 3, 
                    'title' => Input::post('title_shopping'),
                    'message' => Input::post('shopping'),
                ));

                if ($c1->save() && $c2->save() && $c3->save())
                {
                    Session::set_flash('success', "Your comments was successfully saved!");
                    Response::redirect('admin/dashboard');
                }

                $this->template->flash_error = "Failed attempt to save comments".self::THIS_SHOULD_NOT_HAPPEN;
            }
            else
            {
                /**
                 * Sticky
                 */
                $comments[1] = (object) array('title' => Input::post('title_members'), 'message' => Input::post('members'));
                $comments[2] = (object) array('title' => Input::post('title_nonprofits'), 'message' => Input::post('nonprofits'));
                $comments[3] = (object) array('title' => Input::post('title_shopping'), 'message' => Input::post('shopping'));

                /**
                 * Validation error messages
                 */
                $this->errmsg['title_members'] = $val_comment->error('title_members');
                $this->errmsg['title_nonprofits'] = $val_comment->error('title_nonprofits');
                $this->errmsg['title_shopping'] = $val_comment->error('title_shopping');
                $this->errmsg['members'] = $val_comment->error('members');
                $this->errmsg['nonprofits'] = $val_comment->error('nonprofits');
                $this->errmsg['shopping'] = $val_comment->error('shopping');
            }
        }

        if ( ! $comments)
        {
            /**
             * Assign latest comments
             */
            $comments = Model_Topic::get_latest_comments();
        }

        $this->reddo('admin/comment.twig', array(
            'comments' => $comments,
        ));
    }
}
