<?php
class UsersController extends AppController {

	var $name = 'Users';

	function beforeFilter() {
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
			$this->redirect($this->Auth->redirect());
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

	function edit() {
		if (!empty($this->data) && $this->data['User']['id'] == $this->Auth->user('id')) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('User updated');
			} else {
				$this->Session->setFlash('User NOT updated');
			}
		}

		$this->data = $this->User->read(null, $this->Auth->user('id'));
	}


	function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
}
?>