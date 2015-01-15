<?php

class Controller_Admin_Ajax extends Controller_Admin_Base
{
    public function action_set_email_status()
    {
        if (Input::post('id'))
            $id = Input::post('id');
        else
            return 'IMPROPER_CALL';

        if (is_numeric(Input::post('status')))
            $status = Input::post('status');
        else
            return 'IMPROPER_CALL';

        $iemail = Model_Infoemail::find($id);
        $iemail->is_active = $status;

        if ($iemail->save())
            return 1;
        else
            return 'ERROR_ON_SAVE';
    }

    public function action_search($type = null, $search_str = null)
    {
        $empty = array('success' => false, 'data' => array());

        if (is_null($type) || is_null($search_str))
            return json_encode($empty);

        if ($type == 'nonprofit')
            return $this->search_nonprofit($search_str);

        return json_encode($empty);
    }

    public function search_nonprofit($search_str)
    {
        $result = array('success' => true, 'data' => array());

        $nprofits = DB::query("SELECT id, name FROM nonprofits WHERE name LIKE '$search_str%' ORDER BY name")->execute();

        foreach ($nprofits as $nprofit)
            $result['data'][$nprofit['id']] = $nprofit['name'];

        return json_encode($result);
    }

    public function action_invite_nonprofit()
    {
        $result = array(false, "");
        $ar = Model_Nonprofit::find(Input::post('nonprofit_id'));

        if ($ar)
        {
            $result = Model_User::create_user(self::GROUP_NONPROFIT, $ar->name, Input::post('email'));

            if ($result[0] === true)
            {
                $member = Model_Nonprofit::get_selecting_member(Input::post('nonprofit_id'));
                $avatar = Model_User::get_avatar($member['id']);

                $subject = $ar->name . " favorited on Club4Causes!";

                $message = "
                <p>Congratulations " . $ar->name . "</div>
                " . $member['first_name'] . " " . $member['last_name'] . " <img src='$avatar' style='height:120px;width:120px'> has selected you as one of their favorite Non Profits.</p>
                <p>You will be receiving on going donations.</p>
                <p>Please see <a href='http://" . $_SERVER['HTTP_HOST'] . "'>\"HOW IT WORKS\"</a> and <a href='http://" . $_SERVER['HTTP_HOST'] . "/nonprofit/" . Input::post('nonprofit_id') . "'>YOUR PAGE</a>.
                <p>If you have any questions, please use the <a href='http://" . $_SERVER['HTTP_HOST'] . "/contact'>contact page</a> on our website.</p>
                <p>Thank you</p>
                <img src='http://dev.club4causes.com/assets/img/site_logo.png'>
                ";

                if (Helper::send_email($subject, $message, Input::post('email'), $ar->name))
                {
                    $ar->is_contacted = true;
                    if ( ! $ar->save())
                        $result[0] = false;
                }
                else
                    $result[0] = false;
            }
        }

        return json_encode(array('success' => $result[0], 'message' => (isset($result[1]) ? $result[1] : null) ));
    }
}

// eof
