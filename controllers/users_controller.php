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
		$this->Auth->allow('add', 'logout');

		if (!empty($this->data['User']['password'])) {
			$this->data['User']['confirm_password'] = $this->data['User']['password'];
		}
	}

	function login() {
		if ($id = $this->Auth->user('id')) {
			$this->User->id = $id;
			$this->User->save(array('last_login' => date('Y-m-d H:m:s')));
			if (strpos($this->Auth->redirect(), 'user/add') !== false) {
				$this->redirect($this->Auth->redirect());
			} else {
				$this->redirect('/');
			}
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
			$data = $this->data;
			unset($data['User']['username']);
			if ($this->User->save($data)) {
				$this->Session->setFlash('User updated');
			} else {
				$this->Session->setFlash('User NOT updated');
			}
		}

		$this->render('edit');
	}


	function admin_index() {
		$this->User->recursive = 0;
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