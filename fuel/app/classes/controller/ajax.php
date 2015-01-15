<?php

class Controller_Ajax extends Controller_Base
{
    public function action_index()
    {
        return "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur aspernatur sequi quo ut quaerat dolor non ipsa dolorem. Ipsum labore necessitatibus voluptatibus et aliquid quaerat cupiditate optio ipsam impedit iure ullam rerum enim alias odio veritatis illum suscipit fuga facere tempora reprehenderit eum vitae expedita eos dolorum corporis! Voluptates voluptatibus dolorum ratione eius totam tempore unde doloribus assumenda dolor ipsa nobis optio dignissimos debitis natus numquam. Quos nesciunt at molestias praesentium a fuga aut nisi vero quaerat aspernatur et suscipit quibusdam veniam adipisci deleniti mollitia obcaecati sequi dolores doloribus vel doloremque unde cumque ex inventore minima! Quo assumenda at voluptas.";
    }

    public function action_save_reason_for_choosing()
    {
        $result = false;
        $operation = null;

        $this->require_auth();

        $reason_text = trim(Input::post('reason_text'));
        $nonprofit_id = Input::post('nonprofit_id');

        $user = Model_User::find(Session::get('user_id'));

        if ($nonprofit_id && $user)
        {
            if ("" == $reason_text)
            {
                $operation = "delete";

                // Clear reason
                foreach ($user->member->nonprofit_selections as $reason)
                {
                    if ($reason->nonprofit_id == $nonprofit_id)
                    {
                        $reason->reason = "";
                        break;
                    }
                }
                $result = true;
            }
            else
            {
                foreach ($user->member->nonprofit_selections as $reason)
                {
                    if ($reason->nonprofit_id == $nonprofit_id)
                    {
                        $operation = "update";
                        
                        $reason->reason = $reason_text;
                        break;
                    }
                }
            }

            if ($user->member->save())
                $result = true;
        }

        return json_encode(array('success' => $result, 'operation' => $operation));
    }

    public function action_save_member_webcam_image()
    {
        $this->require_auth();

        $img_base64 = Input::post('img_base64');

        if ($img_base64)
        {
            $img_filename = "webcam_" . Session::get('user_id') . ".jpg";
            $target = DOCROOT.DS . "web_data/member/" . $img_filename;

            if (file_put_contents($target, base64_decode($img_base64)))
                return json_encode(array('success' => true, 'img_filename' => $img_filename));
        }

        return json_encode(array('success' => false));
    }

    public function action_member_starts($char)
    {
        $query = Model_User::find()->where(array('first_name', 'like', "{$char}%"))->get();

        $members = array();
        foreach ($query as $user)
        {
            $nonprofits = array();
            foreach ($user->member->nonprofits as $row)
            {
                $nonprofits[] = array(
                    'id' => $row->id,
                    'name' => $row->name,
                );
            }

            $members[] = array(
                'id' => $user->member->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'md5_email' => md5($user->email),
                'running_points' => $user->member->running_points,
                'avatar' => $user->get_avatar($user->id),
                'flag' => Helper::render_flag($user->member->address->country),
                'nonprofits' => $nonprofits,
            );
        }

        header('Content-type: application/json');
        return json_encode($members);
    }

    public function action_nonprofit_starts($char)
    {
        $query = Model_Nonprofit::find()->where(array('name', 'like', "{$char}%"))->get();

        $nonprofits = array();
        foreach ($query as $nonprofit)
        {
            $row = array(
                'id' => $nonprofit->id,
                'name' => $nonprofit->name,
                'running_points' => $nonprofit->running_points,
            );

            if (count($nonprofit->address) == 1)
                $row['flag'] = Helper::render_flag($nonprofit->address->country);
            else
                $row['flag'] = '';

            $nonprofits[] = $row;
        }

        header('Content-type: application/json');
        return json_encode($nonprofits);
    }

    public function action_get_nonprofit_address()
    {
        $arr = array();

        // TODO: improve so that only one query remains.
        // $ar = Model_Nonprofit::query()->where('name', $_GET['nonprofit_name'])->get_one();
        // $addr = Model_Nonprofit_Address::query()->where('nonprofit_id', $ar->id)->get_one();

        $sql = "SELECT country, state, city 
            FROM nonprofit_addresses na 
            LEFT JOIN nonprofits np ON np.id = na.nonprofit_id 
            WHERE np.name = '" . addslashes($_GET['nonprofit_name']) . "'";

        $addr = DB::query($sql)->execute();

        if (count($addr) > 0)
        {
            $addr_one = $addr[0];
            $arr = array(
                'country' => $addr_one['country'],
                'state' => $addr_one['state'],
                'city' => $addr_one['city'],
            );
        }

        return json_encode($arr);
    }

    public function action_recaptcha($privatekey, $remoteip, $challenge, $response)
    {
        $h = curl_init();

        curl_setopt($h, CURLOPT_URL, "http://www.google.com/recaptcha/api/verify"); 
        curl_setopt($h, CURLOPT_POST, true);
        curl_setopt($h, CURLOPT_POSTFIELDS, array(
            'privatekey' => $privatekey,
            'remoteip' => $remoteip,
            'challenge' => $challenge,
            'response' => $response,
        ));
        curl_setopt($h, CURLOPT_HEADER, false);
        curl_setopt($h, CURLOPT_RETURNTRANSFER, 1);

        $result = explode("\n", curl_exec($h));

        return json_encode(array(
            'success' => ($result[0] === 'true'),
            'incorrect' => ($result[0] === 'false'),
            'message' => $result[1],
        ));
    }

    public function action_check_username_availability()
    {
        return json_encode(array(
            'is_taken' => (boolean) Model_User::find()->where('username', Input::get('username', ''))->count(),
        ));
    } 
}

// eof
