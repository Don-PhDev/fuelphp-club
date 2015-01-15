<?php

class Controller_Auth extends Controller_Base
{
    public function action_signin($user_type = "member")
    {
        if (Auth::check())
        {
            Session::set_flash('success', 'Redirected page from login to homepage because you are already signed in!');
            Response::redirect('/');
        }

        $this->require_user('_ANYBODY_');

        if (Input::method() == 'POST')
        {
            $result = Auth::login(Input::post('username'), Input::post('password'));

            if ($result === true)
            {
                list(, $user_id) = Auth::get_user_id();
                $user = Model_User::find($user_id);
                
                Session::set('user_id', $user_id);
                Session::set('user_group', $user->group);

                Session::set_flash('okhide', 'Greetings '.$user->first_name.' '.$user->last_name.', you have signed in successfully.');

                $page = '/';

                if ($request_uri = Session::get('request_uri'))
                {
                    Session::delete('request_uri');
                    $page = $request_uri;
                }
                
                /**
                 * Authentication successful, redirect user to to appropriate page
                 */
                $pra = Session::get('page_requesting_auth');

                if ($pra)
                {
                    Session::delete('page_requesting_auth');
                    Response::redirect($pra);
                }
                else
                {
                    if ("admin" == $user_type)
                        Response::redirect('admin/dashboard');
                    else
                        Response::redirect('my/homepage');
                }
            }
            else if ($result === false)
            {
                Session::set_flash('error', 'Invalid login, please try again.');
            }
            else if ($result === 'USER_NOT_ACTIVATED')
            {
                Session::set_flash('error', 'You have yet to activate your account. We have just sent you an email with activation link in case you missed the first one.');
                Response::redirect('/');
            }
        }

        if ("admin" == $user_type)
            Response::redirect('admin/signin');
        else
            Response::redirect('members/signin');
    }

    public function action_signout()
    {
        if (Auth::check())
        {
            Auth::logout();
            Session::delete('user_id');
            Session::set_flash('okhide', 'Bye '.$this->current_user->first_name.', you have signed out.');
        }
        
        Response::redirect('/');
    }
}
