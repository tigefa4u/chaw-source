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
		$this->Auth->allow('forgotten', 'verify', 'add', 'login', 'logout');
		$this->Access->allow('forgotten', 'verify', 'add', 'login', 'logout', 'account', 'edit', 'change');

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

			if (!empty($this->data['User']['remember_me'])) {
				$this->Cookie->write('User', $this->Session->read('Auth.User'));
			}

			$group = $this->Project->group($id);
			$this->Session->write('Auth.User.Permission', array('group' => $group));

			$this->User->id = $id;
			$this->User->save(array('last_login' => date('Y-m-d H:m:s')), false, array('last_login'));

			$redirect = $this->Auth->redirect();

			if (strpos($redirect, 'login') !== false || strpos($redirect, 'users/add') !== false || $redirect == '/') {
				$redirect = '/dashboard';
			}
			$this->redirect($redirect);

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
			$this->Session->setFlash('Did you forget your password?');
			$this->redirect(array('action' => 'forgotten'));
		}
	}

	function logout() {
		$this->Cookie->del('User');
		$this->Auth->logout();
		$this->redirect('/');
	}

	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('User added');
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

		$isAllowed = ($this->params['isAdmin']
			|| ($this->data['User']['id'] == $this->Auth->user('id'))
			&& $this->data['User']['username'] == $this->Auth->user('username'));

		if (!$isAllowed) {
			$this->render('view');
			return;
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
			'conditions' => array('Permission.project_id' => $this->Project->id)))), false);

		if (empty($this->passedArgs['all'])) {
			$this->paginate['conditions'] = array('Permission.project_id' => $this->Project->id);
			$this->paginate['fields'] = array('User.username', 'User.email', 'User.last_login', 'Permission.id', 'Permission.group');
		}

		$users = $this->paginate();
		$groups = $this->Project->groups();

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

	function forgotten() {
		if ($this->Auth->user()) {
			$this->redirect(array('action' => 'account'));
		}
		if (!empty($this->data)) {
			if ($token = $this->User->forgotten($this->data)) {
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
			if ($data = $this->User->verify(compact('token'))) {
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
}
?>