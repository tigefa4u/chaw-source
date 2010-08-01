<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
 */
class UsersController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Users';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $components = array(
		'Email', 'Cookie' => array('name' => 'Chaw', 'time' => '+2 weeks'),
		'Gpr' => array(
			'keys' => array('username'), 'actions' => array('admin_index')
		)
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->autoRedirect = false;
		$this->Auth->mapActions(array(
			'account' => 'update', 'change' => 'update'
		));
		$this->Auth->allow('forgotten', 'verify', 'add', 'logout');
		$this->Access->allow('forgotten', 'verify', 'activate', 'add', 'login', 'logout', 'account', 'edit', 'change');

		if (!empty($this->data['User']['password'])) {
			$this->data['User']['confirm_password'] = $this->data['User']['password'];
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		$this->redirect(array('action' => 'account'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function view() {
		$this->redirect(array('action' => 'account'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function login() {
		if ($cookie = $this->Cookie->read('User')) {
			$this->Session->write('Auth.User', $cookie);
		}

		if ($id = $this->Auth->user('id')) {
			if (!empty($this->data['User']['remember_me'])) {
				$this->Cookie->write('User', $this->Session->read('Auth.User'));
			}

			$this->Session->write('Auth.User.Permission', $this->User->groups($id));

			$this->User->id = $id;
			$this->User->save(array(
				'id' => $id,
				'username' => $this->Auth->user('username'),
				'email' => $this->Auth->user('email'),
				'last_login' => date('Y-m-d H:i:s')
			));

			if ($redirect = $this->Session->read('Access.redirect')) {
				$this->Session->delete('Access');
				$this->Session->delete('Auth.redirect');
				$message = "access:$redirect";
			} else {
				$redirect = $this->Auth->redirect();
				$message = "auth:$redirect";
			}
			if (strpos($redirect, 'login') !== false || strpos($redirect, 'users/add') !== false || $redirect == '/') {
				$redirect = '/dashboard';
			}
			$this->redirect($redirect);
			//$this->flash($message, $redirect);
			// /return;
		}

		if (!empty($this->data['User'])) {
			$this->Session->delete('Message.auth');

			$this->Auth->fields['password'] = 'tmp_pass';
			$this->data['User']['tmp_pass'] = $this->Auth->data['User']['password'];

			if ($this->Auth->login($this->data)) {
				$this->User->id = $this->Auth->user('id');
				$this->User->save(array(
					'id' => $id,
					'username' => $this->Auth->user('username'),
					'email' => $this->Auth->user('email'),
					'last_login' => date('Y-m-d H:i:s'),
					'tmp_pass' => null,
				));
				$this->Session->setFlash(__('You may now change your password',true));
				$this->redirect(array('action' => 'change'));
			}
		}

		if (!empty($this->data['User']['username'])) {
			$this->Session->delete('Message.auth');
			$this->Session->setFlash(__('Did you forget your password?',true));
			$this->redirect(array('action' => 'forgotten'), 401);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function logout() {
		$this->Cookie->destroy('User');
		$this->Auth->logout();
		$this->Session->delete('Access');
		$this->Session->delete('Auth.redirect');
		$this->redirect('/');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($data = $this->User->save($this->data)) {
				$this->Session->setFlash(sprintf(__('%1$s is now registered',true), $data['User']['username']));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__('User NOT added',true));
			}

			$this->data['User']['password'] = $this->data['User']['confirm_password'];
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function account() {
		$id = $this->Auth->user('id');
		if (!$id) {
			$this->redirect(array('action' => 'login'));
		}
		$this->edit($id);
		$this->render('edit');
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function edit($id = null) {
		if (!$id && !empty($this->passedArgs[0])) {
			$id = $this->passedArgs[0];
		}

		$isGet = false;
		if (empty($this->data)) {
			$isGet = true;
			$this->data = $this->User->read(null, $id);
		}

		$isAllowed = (
			($this->params['isAdmin'] && $this->Project->id == 1) ||
			($this->data['User']['id'] == $this->Auth->user('id') &&
			$this->data['User']['username'] == $this->Auth->user('username'))
		);

		if (!$isAllowed) {
			echo $this->render('view');
			exit;
		}

		if ($isGet === false) {
			if ($data = $this->User->save($this->data)) {
				$this->Session->setFlash(__('User updated',true));
			} else {
				$this->Session->setFlash(__('User NOT updated',true));
			}
			unset($this->data['SshKey']);
		}

		$types = $this->Project->repoTypes();

		$sshKeys = array();

		foreach ($types as $type) {
			$sshKeys[$type] = $this->User->SshKey->read(array(
				'type' => $type,
				'username' => $this->data['User']['username']
			));
		}

		$this->set(compact('sshKeys', 'types'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_index() {
		if (!empty($this->data['Permission'])) {
			foreach ($this->data['Permission'] as $permission) {
				$this->User->Permission->id = $permission['id'];
				$this->User->Permission->save($permission);
			}
			$this->data = array();
		}
		if (!empty($this->data['User']['username']) && !empty($this->data['User']['group'])) {
			if ($id = $this->User->field('id', array('username' => $this->data['User']['username']))) {
				if ($this->Project->permit($id, $this->data['User']['group'])) {
					$this->Session->setFlash(sprintf(__('%s added',true)),$this->data['User']['username']);
				}
			} else {
				$this->Session->setFlash(sprintf(__('%s was not found',true),$this->data['User']['username']));
			}
		}

		$this->User->unbindModel(array('hasOne' => array('Permission')), false);
		$this->User->bindModel(array('hasOne' => array('Permission' => array(
			'conditions' => array('Permission.project_id' => $this->Project->id
		)))), false);

		$this->paginate['order'] = 'Permission.group ASC';
		$this->paginate['fields'] = array(
			'User.id', 'User.username', 'User.email', 'User.last_login',
			'Permission.id', 'Permission.group'
		);

		$this->paginate['conditions'] = array('Permission.project_id' => $this->Project->id);

		if (!empty($this->passedArgs['all']) && ($this->params['isAdmin'] && $this->Project->id == 1)) {
			$this->paginate['conditions'] = array();
		} else {
			$groups = $this->Project->groups();
		}

		if (!empty($this->passedArgs['username'])) {
			$this->paginate['conditions'] = array('User.username' => $this->passedArgs['username']);
		}

		$users = $this->paginate();

		$this->set(compact('users', 'groups'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_add() {
		$this->add();
		$this->render('add');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_edit() {
		$this->edit();
		$this->render('edit');
	}

	/**
	 * undocumented function
	 *
	 * @param string $token
	 * @return void
	 */
	function activate($token = null) {
		if (empty($token)) {
			if ($data = $this->User->setToken($this->Auth->user())) {
				$from = $this->Project->from();
				/*
				$this->Email->delivery = 'smtp';
				$this->Email->smtpOptions = array(
					'port' => 465,
					'timeout' => 30,
					'host' => 'ssl://smtp.gmail.com',
					'username' => '',
					'password' => ''
				);
				*/
				$this->Email->to = "{$data['User']['username']} <{$data['User']['email']}>";
				$this->Email->from = "Chaw Activation {$from}";
				$this->Email->replyTo = "Chaw Activation {$from}";
				$this->Email->return = $from;
				$this->Email->subject = 'Activate your Chaw account';

				$content[] = "Please click on the link below to activate your account.\n";
				$content[] = Router::url(array('controller' => 'users', 'action' => 'activate', $data['User']['token']), true);

				$this->Email->lineLength = 120;
				if ($this->Email->send($content)) {
					$this->Session->setFlash(__('Check your email.',true));
				} else {
					$this->Session->setFlash(__('Email was not sent',true));
				}
			} else {
				$this->Session->setFlash(__('User could not be found',true));
			}
			$this->redirect($this->referer());
		} else {
			if ($data = $this->User->activate($token)) {
				$this->Session->write('Auth.User.active', $data['User']['active']);
				$this->Session->setFlash(__('Your Account was activated',true));
			} else {
				$this->Session->setFlash(__('Your Account could not be activated',true));
			}
		}

		$this->redirect('/dashboard');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function forgotten() {
		if ($this->Auth->user()) {
			$this->redirect(array('action' => 'account'));
		}
		if (!empty($this->data)) {
			if ($token = $this->User->setToken($this->data)) {
				$from = $this->Project->from();
				/*
				$this->Email->delivery = 'smtp';
				$this->Email->smtpOptions = array(
					'port' => 465,
					'timeout' => 30,
					'host' => 'ssl://smtp.gmail.com',
					'username' => '',
					'password' => ''
				);
				*/
				$this->Email->to = "{$token['User']['username']} <{$token['User']['email']}>";
				$this->Email->from = "Chaw Password Recovery {$from}";
				$this->Email->replyTo = "Chaw Password Recovery {$from}";
				$this->Email->return = $from;
				$this->Email->subject = 'Password Recovery URL';

				$content[] = "A request to reset your password has been submitted.\n";
				$content[] = "Please visit the following URL to have your temporary password";
				$content[] = "sent to the e-mail address associated with this account.\n";
				$content[] = Router::url(array('controller' => 'users', 'action' => 'verify', $token['User']['token']), true);

				$this->Email->lineLength = 120;
				if ($this->Email->send($content)) {
					$this->Session->setFlash(__('Check your email to verify your request',true));
				}
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $token
	 * @return void
	 */
	function verify($token = null) {
		if (!empty($token)) {
			if ($data = $this->User->setTempPassword(compact('token'))) {
				$from = $this->Project->from();
				/*
				$this->Email->delivery = 'smtp';
				$this->Email->smtpOptions = array(
					'port' => 465,
					'timeout' => 30,
					'host' => 'ssl://smtp.gmail.com',
					'username' => '',
					'password' => ''
				);
				*/
				$this->Email->to = "{$data['User']['username']} <{$data['User']['email']}>";
				$this->Email->from = "Chaw New Password {$from}";
				$this->Email->replyTo = "Chaw New Password {$from}";
				$this->Email->return = $from;
				$this->Email->subject = 'New Password';

				$content[] = "The request to reset your password has been verifed.\n";
				$content[] = "Username: " . $data['User']['username'] ."\n";
				$content[] = "Your temporary password: " . $data['User']['tmp_pass'] ."\n";
				$content[] = "Please Login with your temporary password.";
				$content[] = Router::url(array('controller' => 'users', 'action' => 'login'), true);

				$this->Email->lineLength = 120;
				if ($this->Email->send($content)) {
					$this->Session->setFlash(__('Check your email for your new password.',true));
				}
			}
		}
		$this->redirect(array('action' => 'login'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function change() {
		if (!empty($this->data)) {
			$this->User->id = $this->Auth->user('id');
			$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			if ($this->User->save($this->data, false, array('password'))) {
				$this->Session->setFlash(__('Your password was changed',true));
				$this->redirect(array('action' => 'account'));
			} else {
				$this->Session->setFlash(__('Your password was NOT changed',true));
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function admin_remove($id = null) {
		if ($id && $this->Project->id == 1 && !empty($this->params['isAdmin'])) {
			$this->User->id = $id;
			if ($this->User->save(array('active' => 0), false, array('active'))) {
				$this->User->Permission->deleteAll(array('Permission.user_id' => $id));
				$this->Session->setFlash(__('User was removed',true));
			} else {
				$this->Session->setFlash(__('User was NOT changed',true));
			}
		}
		$this->redirect($this->referer());
	}
}
?>