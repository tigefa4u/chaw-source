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
 * @subpackage		chaw.controllers.components
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class AccessComponent extends Object {
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $access = 'r';
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $user = array();
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $url = false;
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $isPublic = true;
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $isAllowed = false;
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $allowedActions = array();
/**
 * initialize
 *
 * @return void
 *
 **/
	function initialize(&$C) {
		$C->params['isAdmin'] = false;
		$this->isAllowed = false;
		$this->isPublic = true;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function startup(&$C) {
		if ($C->name == 'CakeError') {
			return $this->enabled = false;
		}

		$this->url = $C->params['url']['url'];

		if (empty($this->user)) {
			$this->user = $C->Auth->user();
		}

		if (empty($C->Project)) {
			if ($this->url != 'start') {
				$C->Session->setFlash('Chaw needs to be installed');
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'start'));
				return false;
			}

			$C->Session->write('Install', true);
			$C->Auth->allow('start');
			return true;
		}

		$allowedByAuth = in_array($C->action, $C->Auth->allowedActions);
		$this->isAllowed = in_array($C->action, $this->allowedActions);

		if ($C->Project->initialize($C->params) === false) {

			if ($C->Session->read('Install') !== true) {
				$C->Session->setFlash('Chaw needs to be installed');
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'start'));
				return false;
			}

			if ($this->user()) {
				if (!in_array($this->url, array('projects/add'))) {
					$C->Session->setFlash('Chaw needs to be installed');
					$C->redirect(array(
						'admin' => false, 'project' => false,
						'controller' => 'pages', 'action'=> 'start'
					));
					return false;
				}
				$C->params['isAdmin'] = true;
				return true;
			}

			if (!$allowedByAuth) {
				$login =  Router::url(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'login'));
				$C->Session->setFlash("Chaw needs to be installed. Please <a href='{$login}'>Login</a> or Register");
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'add'));
				return false;
			}
			$C->Auth->allow('add');
			return true;
		}

		if ($isOwner = ($this->user('id') == $C->Project->config['user_id'])) {
			$C->params['isOwner'] = true;
		}

		$C->Auth->allow('index');

		if ($this->url == 'projects') {
			$this->isAllowed = true;
		}

		if (!empty($C->Project->config['private'])) {
			$this->isPublic = false;
		}

		if ($this->check($C)) {
			if ($this->isAllowed) {
				return true;
			}
		}

		if (!$this->user() && !$this->isAllowed && !$allowedByAuth) {
			$C->Auth->deny($C->action);
			$C->Auth->authError = "Please login to continue.";
			return false;
		}

		if ($this->isPublic === false) {
			if (!$this->isAllowed && !$allowedByAuth) {
				$C->Session->setFlash('Select a Project');
				$C->redirect(array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'projects', 'action' => 'index'
				));
				return false;
			}
		}

		if ($this->user() && !$this->isAllowed) {
			$C->Session->setFlash($C->Auth->authError);
			$C->redirect($C->referer());
			return false;
		}

		return true;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function check(&$C) {

		$crud = $this->access = 'r';
		if (!empty($C->Auth->actionMap[$C->params['action']])) {
			$crud = $C->Auth->actionMap[$C->params['action']][0];
		}
		if (in_array($crud, array('c', 'u', 'd'))) {
			$this->access = 'w';
		}

		$username = $this->user('username');

		if (!empty($username)) {
			$admin = array(
				'user' => $username,
				'access' => array($this->access, $crud),
				'default' => false
			);

			$allowAdmin = $C->Project->Permission->check('admin', $admin);

			if ($allowAdmin === true) {
				return $C->params['isAdmin'] = $this->isAllowed = true;
			}
		}

		if ($this->isAllowed) {
			return true;
		}

		if ($this->access == 'w' && !$username) {
			return $this->isAllowed = false;
		}

		$default = ((!$username && $this->access == 'w') || !empty($C->params['admin'])) ? false : $this->isPublic;

		$user = array(
			'user' => $username,
			'access' => array($this->access, $crud),
			'default' => $default
		);

		$allowUser = $C->Project->Permission->check($C->params['controller'], $user);

		if ($allowUser === true) {
			return $this->isAllowed = true;
		}

		return false;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function user($key = null) {
		if (empty($this->user)) {
			return false;
		}

		$field = null;
		if ($key === null) {
			return $this->user['User'];
		}

		if (strpos($key, '.') !== false) {
			list($key, $field) = explode('.', $key);
		} else {
			$field = $key;
			$key = 'User';
		}

		if (!empty($this->user[$key][$field])) {
			return $this->user[$key][$field];
		}

		return false;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function allow($actions = array()) {
		if (!is_array($actions)) {
			$actions = func_get_args();
		}

		$this->allowedActions = array_merge($this->allowedActions, $actions);
	}
}