<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class UsersController extends AppController {

	var $name = 'Users';

	var $components = array('Email', 'Cookie' => array('name' => 'Chaw', 'time' => '+2 weeks'));

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

	function index() {
		$this->redirect(array('action' => 'account'));
	}
	function view() {
		$this->redirect(array('action' => 'account'));
	}

	function login() {
		if ($cookie = $this->Cookie->read('User')) {
			$this->Session->write('Auth.User', $cookie);
		}

		if ($id = $this->Auth->user('id')) {
			if (!empty($cookie)) {
				die(debug($_SESSION));
				die(debug($_COOKIE));
			}

			if (!empty($this->data['User']['remember_me'])) {
				$this->Cookie->write('User', $this->Session->read('Auth.User'));
			}

			$this->Session->write('Auth.User.Permission', $this->User->groups($id));

			$this->User->id = $id;
			$this->User->save(array('last_login' => date('Y-m-d H:m:s')), false, array('last_login'));

			if ($redirect = $this->Session->read('Access.redirect')) {
				$this->Session->del('Access');
				$this->Session->del('Auth.redirect');
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
			//return;
		} elseif (strpos($this->referer(), 'users/login') && !empty($this->data['User'])) {

			$this->Session->del('Message.auth');

			$this->Auth->fields['password'] = 'tmp_pass';
			$this->data['User']['tmp_pass'] = $this->Auth->data['User']['password'];
			if ($this->Auth->login($this->data)) {
				$this->User->id = $this->Auth->user('id');
				$this->User->save(array(
					'last_login' => date('Y-m-d H:m:s'),
					'tmp_pass' => null,
					),
					false, array('last_login', 'tmp_pass')
				);
				$this->Session->setFlash('You may now change your password');
				$this->redirect(array('action' => 'change'));
			}
		}

		if (!empty($this->data['User']['username'])) {
			$this->Session->del('Message.auth');
			$this->Session->setFlash('Did you forget your password?');
			$this->redirect(array('action' => 'forgotten'), 401);
		}
	}

	function logout() {
		$this->Cookie->destroy('User');
		$this->Auth->logout();
		$this->Session->del('Access');
		$this->Session->del('Auth.redirect');
		$this->redirect('/');
	}

	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($data = $this->User->save($this->data)) {
				$this->Session->setFlash(sprintf('%1$s is now registered', $data['User']['username']));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash('User NOT added');
			}

			$this->data['User']['password'] = $this->data['User']['confirm_password'];
		}
	}

	function account() {
		$id = $this->Auth->user('id');
		if (!$id) {
			$this->redirect(array('action' => 'login'));
		}
		$this->edit($id);
	}

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
				$this->Session->setFlash('User updated');
			} else {
				$this->Session->setFlash('User NOT updated');
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
		$this->render('edit');
	}

	function admin_index() {
		if (!empty($this->data['Permission'])) {
			foreach ($this->data['Permission'] as $permission) {
				$this->User->Permission->id = $permission['id'];
				$this->User->Permission->save($permission);
			}
		}
		if (!empty($this->data['User']['username'])) {
			if ($id = $this->User->field('id', array('username' => $this->data['User']['username']))) {
				if ($this->Project->permit($id, $this->data['User']['group'])) {
					$this->Session->setFlash($this->data['User']['username'] . ' added');
				}
			} else {
				$this->Session->setFlash($this->data['User']['username'] .' was not found');
			}
		}

		$this->User->unbindModel(array('hasOne' => array('Permission')), false);
		$this->User->bindModel(array('hasOne' => array('Permission' => array(
			'conditions' => array('Permission.project_id' => $this->Project->id
		)))), false);

		$this->paginate['fields'] = array('User.id', 'User.username', 'User.email', 'User.last_login', 'Permission.id', 'Permission.group');
		$this->paginate['conditions'] = array('Permission.project_id' => $this->Project->id, 'User.active' => 1);

		if (!empty($this->passedArgs['all']) && ($this->params['isAdmin'] && $this->Project->id == 1)) {
			$this->paginate['conditions'] = array('User.active' => 1);
		} else {
			$groups = $this->Project->groups();
		}

		$users = $this->paginate();

		$this->set(compact('users', 'groups'));
	}

	function admin_add() {
		$this->add();
		$this->render('add');
	}
	function admin_edit() {
		$this->edit();
		$this->render('edit');
	}

	function activate($token = null) {
		if (empty($token)) {
			if ($data = $this->User->setToken($this->Auth->user())) {
				$from = $this->Project->from();
				$this->Email->to = $data['User']['email'];
				$this->Email->from = 'Chaw Activation ' . $from;
				$this->Email->replyTo = 'Chaw Activation ' . $from;
				$this->Email->return = $from;
				$this->Email->subject = 'Activate your Chaw account';

				$content[] = "Please click on the link below to activate your account.\n";
				$content[] = Router::url(array('controller' => 'users', 'action' => 'activate', $data['User']['token']), true);

				$this->Email->lineLength = 120;
				if ($this->Email->send($content)) {
					$this->Session->setFlash('Check your email.');
				} else {
					$this->Session->setFlash('Email was not sent');
				}
			} else {
				$this->Session->setFlash('User could not be found');
			}
			$this->redirect($this->referer());
		} else {
			if ($data = $this->User->activate($token)) {
				$this->Session->write('Auth.User.active', $data['User']['active']);
				$this->Session->setFlash('Your Account was activated');
			} else {
				$this->Session->setFlash('Your Account could not be activated');
			}
		}

		$this->redirect('/dashboard');
	}

	function forgotten() {
		if ($this->Auth->user()) {
			$this->redirect(array('action' => 'account'));
		}
		if (!empty($this->data)) {
			if ($token = $this->User->setToken($this->data)) {
				$from = $this->Project->from();
				$this->Email->to = $token['User']['email'];
				$this->Email->from = 'Chaw Password Recovery ' . $from;
				$this->Email->replyTo = 'Chaw Password Recovery ' . $from;
				$this->Email->return = $from;
				$this->Email->subject = 'Password Recovery URL';

				$content[] = "A request to reset your password has been submitted.\n";
				$content[] = "Please visit the following URL to have your temporary password";
				$content[] = "sent to the e-mail address associated with this account.\n";
				$content[] = Router::url(array('controller' => 'users', 'action' => 'verify', $token['User']['token']), true);

				$this->Email->lineLength = 120;
				if ($this->Email->send($content)) {
					$this->Session->setFlash('Check your email to verify your request');
				}
			}
		}
	}

	function verify($token = null) {
		if (!empty($token)) {
			if ($data = $this->User->setTempPassword(compact('token'))) {
				$from = $this->Project->from();
				$this->Email->to = $data['User']['email'];
				$this->Email->from = 'Chaw New Password ' . $from;
				$this->Email->replyTo = 'Chaw New Password ' . $from;
				$this->Email->return = $from;
				$this->Email->subject = 'New Password';

				$content[] = "The request to reset your password has been verifed.\n";
				$content[] = "Your temporary password: " . $data['User']['tmp_pass'] ."\n";
				$content[] = "Please Login with your temporary password.";
				$content[] = Router::url(array('controller' => 'users', 'action' => 'login'), true);

				$this->Email->lineLength = 120;
				if ($this->Email->send($content)) {
					$this->Session->setFlash('Check your email for your new password.');
				}
			}
		}
		$this->redirect(array('action' => 'login'));
	}

	function change() {
		if (!empty($this->data)) {
			$this->User->id = $this->Auth->user('id');
			$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			if ($this->User->save($this->data, false, array('password'))) {
				$this->Session->setFlash('Your password was changed');
				$this->redirect(array('action' => 'account'));
			} else {
				$this->Session->setFlash('Your password was NOT changed');
			}
		}
	}

	function admin_remove($id = null) {
		if ($id && $this->Project->id == 1 && !empty($this->params['isAdmin'])) {
			$this->User->id = $id;
			if ($this->User->save(array('active' => 0), false, array('active'))) {
				$this->User->Permission->deleteAll(array('Permission.user_id' => $id));
				$this->Session->setFlash('User was removed');
			} else {
				$this->Session->setFlash('User was NOT changed');
			}
		}
		$this->redirect($this->referer());
	}
}
?>