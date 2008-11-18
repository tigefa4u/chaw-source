<?php
/**
 * Short description
 *
 * Long description
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class UsersController extends AppController {

	var $name = 'Users';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->autoRedirect = false;
		$this->Auth->allow('add', 'login', 'logout');
		$this->Access->allow('account', 'edit');

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
		if ($id = $this->Auth->user('id')) {
			$this->User->id = $id;
			$this->User->save(array('last_login' => date('Y-m-d H:m:s')), false, array('last_login'));

			$redirect = $this->Auth->redirect();

			if (strpos($redirect, 'users/add') !== false) {
				$this->redirect(array());
			}
			if ($redirect == '/') {
				$redirect = '/users/account';
			}
			$this->redirect($redirect);
		}
	}

	function logout() {
		$this->redirect($this->Auth->logout());
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

		$isAllowed = ($this->params['isAdmin'] || $this->data['User']['id'] == $this->Auth->user('id'));

		if (!$isAllowed) {
			$this->render('view');
			return;
		}

		if ($isGet === false) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('User updated');
			} else {
				//pr($this->User->validationErrors);
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
		$this->User->recursive = 0;
		if (!empty($this->params['project']) && $this->Project->id !== '1') {
			$this->paginate['conditions'] = array('Permission.project_id' => $this->Project->id);
		}
		$this->paginate['fields'] = array('DISTINCT User.username', 'User.email');

		$this->set('users', $this->paginate());
	}

	function admin_add() {
		$this->add();
		$this->render('add');
	}
	function admin_edit() {
		$this->edit();
		$this->render('edit');
	}
}
?>