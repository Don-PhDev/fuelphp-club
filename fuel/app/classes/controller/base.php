<?php

class Controller_Base extends Controller_Template
{
	public $template;
	public $value;
	public $errmsg;
	public $placeholder;
	public $current_user;
	public $current_member;

	/**
	 * $ids is an object of user, member
	 */
	public $ids;

	/**
	 * Constant Definitions
	 */
	const APP_TITLE = 'Club4Causes';
	const APP_EMAIL = 'membersonly@club4causes.com';
	const APP_NAME = 'Club4Causes Web Application';
	const APP_CONTACT_ADMIN = 'Contact Club4Causes administrator';
	const APP_SALT = 'Fasdfm90a3#$%asdlkfS09adsvm4AS290';

	const MY_URI_ACTION = 2;
	const SECONDS_IN_A_DAY = 86400;

	const USER_MEMBER = 1;

	const THIS_SHOULD_NOT_HAPPEN = '. This should not happen!';
	const ERROR_TRY_AGAIN = 'Please review your entries then try again.';

	const IS_INDEX_FIRST_ROW = 0;

	const PROGRAMMERS_EMAIL = 'mpgacct2005@gmail.com';
	const PROGRAMMERS_NAME = 'Raymond S. Usbal';
	const SUPERUSER_ID = 1;

	const GROUP_GUEST = 0;
	const GROUP_MEMBER = 1;
	const GROUP_NONPROFIT = 888;
	const GROUP_SUPERUSER = 999;

	public function before()
	{
		$this->template = 'reddo.twig';

		parent::before();

		if (Auth::check())
		{
			$ids = array(
				'user' => false,
				'member' => false,
			);

	        list(, $user_id) = Auth::get_user_id();

	        $ids['user'] = $user_id;

	        $user = Model_User::find($user_id);
	        $this->current_user = $user;

			define('SUPERUSER_MODE', (self::SUPERUSER_ID == $user->id));

			$this->_define_this_user($this->current_user->group);

			if (_MEMBER_)
			{
				$this->current_member = $this->current_user->member;
				$ids['member'] = $this->current_member->id;
			}

			/**
			 * Set global Ids of logged user
			 */
			$this->ids = (object) $ids;
		} 
		else 
		{
			define('SUPERUSER_MODE', false);

			$this->current_user = null;
			$this->_define_this_user();
		}

		$this->template->show_header_content = false;

		$this->template->C4c = (object) array(
			'theme' => C4c::THEME,
		);

		$this->template->_ANYBODY_ = _ANYBODY_;
		$this->template->_ADMINISTRATOR_ = _ADMINISTRATOR_;
		$this->template->_SUPERUSER_ = _SUPERUSER_;
		$this->template->_MEMBER_ = _MEMBER_;
		$this->template->_LOGGED_USER_ = _LOGGED_USER_;

		$this->template->styles = array('jquery-ui.css');

		$this->template->stylev = array(
			'jquery-ui.css' => 100, 
			C4c::THEME.'-style.css' => 101,
		);
		
		$this->template->top_scripts = array(
			'jquery.js',
			'jquery-ui.js',
		);

		$this->template->cdn_scripts = array();

		$this->template->scripts = C4c::javascript();

		$this->template->scriptv = array(
			'common.js' => 101, 
		);

		$this->template->flash_okhide = e((array) Session::get_flash('okhide'));
		$this->template->flash_success = e((array) Session::get_flash('success'));
		$this->template->flash_error = e((array) Session::get_flash('error'));

		$this->value = array();
		$this->errmsg = array();
		$this->placeholder = array();

		View::set_global('current_user', $this->current_user);
		View::set_global('current_member', $this->current_member);
		View::set_global('Uri', Uri::segments());
	}

	public function reddo($file = null, $data = null, $auto_filter = null)
	{
		if ( ! isset($this->template->title))
			/**
			 * Always assign a value to Page title
			 */
			if ( ! isset($data['title']))
				$this->template->title = self::APP_TITLE;
			else
				$this->template->title = $data['title'];
		else
			/**
			 * Assign Page title to Data title
			 */
			if ( ! isset($data['title']))
				$data['title'] = $this->template->title;

		if (Input::method() == 'POST')
		{
			foreach ($this->errmsg as $one_err)
			{
				if (false !== $one_err)
				{
					if (false !== $this->template->flash_error)
						if ( ! $this->template->flash_error)
							$this->template->flash_error = self::ERROR_TRY_AGAIN;
					break;
				}
			}
		}

		$data = (array) $data;

		if ($this->value)
			$data = array_merge($data, array('value' => (object) $this->value));

		if ($this->errmsg)
			$data = array_merge($data, array('errmsg' => (object) $this->errmsg));

		if ($this->placeholder)
			$data = array_merge($data, array('placeholder' => (object) $this->placeholder));

		$data['C4c'] = array(
			'theme' => C4c::THEME,
			'no_image' => C4c::NO_IMAGE,
			'recaptcha_private_key' => C4c::RECAPTCHA_PRIVATE_KEY,
			'recaptcha_public_key' => C4c::RECAPTCHA_PUBLIC_KEY,
		);

		$timestamp = time();
		/**
		 * Check if view-specific css exists
		 */
		$h = 'assets/css/view/' . str_replace('-', '_', basename($file, '.twig')) . '.css';
		$this->template->view_css_head = file_exists(DOCROOT.DS . $h) ? $h . '?random=' . $timestamp : "";

		/**
		 * Check if view-specific javascript exists
		 */
		$h = 'assets/js/view/' . str_replace('-', '_', basename($file, '.twig')) . '.js';
		$this->template->view_js_foot = file_exists(DOCROOT.DS . $h) ? $h . '?random=' . $timestamp : "";

		$this->template->content = View::forge($file, $data, $auto_filter);
	}

	public function require_user($required_access, $param_id = null, $with_flash_message = true, $redirect_to_login = null)
	{
		if ('_ANYBODY_' == $required_access || SUPERUSER_MODE) return;

		if (constant($required_access))
		{
			if (is_null($param_id))
				/**
				 * No further check needed
				 */
				return;
			else
			{
				if (is_null($this->current_user))
				{
					/**
					 * User must be signed in
					 */
					if ($with_flash_message)
						Session::set_flash('error', $user_label[$required_access] . ' access is required to access ' . ucwords(Uri::segment(1) . ' ' . Uri::segment(2)));
					Session::set('request_uri', $_SERVER['REQUEST_URI']);
					Response::redirect($redirect_to_login ? $redirect_to_login : 'users/login');
				}

				if ('_MEMBER_' == $required_access)
				{
					if ($this->current_member && $this->current_member->id == $param_id)
						/**
						 * This member id is the same as the logged user
						 */
						return;
					else
					{
						if ($with_flash_message)
							Session::set_flash('error', 'No access, this belong to another member.');
						Response::redirect('/');
					}
				}

				if ($this->current_user->id == $param_id)
				{
					/**
					 * This user id is the same as the logged user
					 */
					return;
				}
			}
		}

		$user_label = array(
			'_ADMINISTRATOR_' => 'Administrator',
			'_SUPERUSER_' => 'Superuser',
			'_MEMBER_' => 'Registered Member',
			'_LOGGED_USER_' => 'Signed-in User',
		);

		if ($this->current_user)
		{
			/**
			 * Already logged but access is not enough
			 */
			if ($with_flash_message)
				Session::set_flash('error', 'Only ' . $user_label[$required_access] . 's have access to the page ' . ucwords(Uri::segment(1) . ' ' . Uri::segment(2)));
			Response::redirect('members/view/' . $this->current_user->id);
		}
		else
		{
			if ($with_flash_message)
				Session::set_flash('error', $user_label[$required_access] . ' access is required to access ' . ucwords(Uri::segment(1) . ' ' . Uri::segment(2)));
			Session::set('request_uri', $_SERVER['REQUEST_URI']);
			Response::redirect($redirect_to_login ? $redirect_to_login : 'users/login');
		}
	}

	private function _define_this_user($group = 0)
	{
		/**
		 * -1   => array('name' => 'Banned', 'roles' => array('banned')),
		 * 0    => array('name' => 'Guests', 'roles' => array()),
		 * 1    => array('name' => 'Users', 'roles' => array('user')),
		 * 50   => array('name' => 'Moderators', 'roles' => array('user', 'moderator')),
		 * 100  => array('name' => 'Administrators', 'roles' => array('user', 'moderator', 'admin')),
		 */

		if (self::GROUP_GUEST == $group)
	 	{
			define('_ANYBODY_', true);
			define('_ADMINISTRATOR_', false);
			define('_SUPERUSER_', false);
			define('_MEMBER_', false);
			define('_LOGGED_USER_', false);

			$this->template->user_access_type = '';
		}
		else
		{
			define('_ANYBODY_', false);
			define('_LOGGED_USER_', true);

			if (self::GROUP_MEMBER == $group)
			{
				define('_ADMINISTRATOR_', false);
				define('_SUPERUSER_', false);

				define('_MEMBER_', true);

				$this->template->user_access_type = 'Member';
			}
			else if (self::GROUP_NONPROFIT == $group)
			{
				define('_SUPERUSER_', false);

				define('_ADMINISTRATOR_', true);
				define('_MEMBER_', true);

				$this->template->user_access_type = 'Nonprofit';
			}
			else if (self::GROUP_SUPERUSER == $group)
			{
				define('_MEMBER_', false);

				define('_SUPERUSER_', true);
				define('_ADMINISTRATOR_', true);

				$this->template->user_access_type = 'Superuser';
			}
		}
	}

	/**
	 * When the need arises, we can use this function.
	 *
	 * public function after($response)
	 * {
	 * 	return parent::after($response);
	 * }
	 */

	public function set_message_failed_attempt_to($msg)
	{
		$this->template->flash_error = 'Failed attempt to ' . $msg . self::THIS_SHOULD_NOT_HAPPEN;
	}

	protected function _get_placeholder($object_name)
	{
		switch ($object_name)
		{
			case 'MemberRegistration':
			case 'MemberUpdate':
			default:
				return (object) array(
				);
		}
	}

	public function require_auth_get_member()
	{
		self::require_auth();
		return Model_Member::query()->where(array('user_id', Session::get('user_id')))->get_one();
	}

    public function require_auth($err_msg = "Please sign in first before accessing your homepage.")
    {
        if ( ! Auth::check())
        {
            Session::set('page_requesting_auth', $_SERVER['REQUEST_URI']);
            Session::set_flash('error', $err_msg);
            Response::redirect('/');
        }
    }

	public function action_404()
	{
		$title = 'Page not found!';
		$message = 'Please check URL, cannot generate requested page.';
		$this->template->title = '404 &raquo; '.$title;
		$this->template->content = View::forge(C4c::THEME.'/404.twig', array('title' => $title, 'message' => $message), 404);
	}
}