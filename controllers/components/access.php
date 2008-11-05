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
 * initialize
 *
 * @return void
 *
 **/
	function initialize(&$C) {
		$C->params['isAdmin'] = false;

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
			}
			return true;
		}

		if ($C->Project->initialize($C->params) === false) {

			if ($C->Session->read('Install') !== true) {
				$C->Session->write('Install', true);
				$C->Session->setFlash('Chaw needs to be installed');
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'start'));
			}
			if ($this->user()) {
				$C->params['isAdmin'] = true;
				if (!in_array($this->url, array('install', 'admin/projects/add'))) {
					$C->Session->setFlash('Chaw needs to be installed');
					$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'start'));
				}
				return true;
			}

			if (!in_array($this->url, array('users/add', 'users/login', 'users/logout'))) {
				$login =  Router::url(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'login'));
				$C->Session->setFlash("Chaw needs to be installed. Please <a href='{$login}'>Login</a> or Register");
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'add'));
			}

			return true;
		}

		if ($isOwner = ($this->user('id') == $C->Project->config['user_id'])) {
			$C->params['isAdmin'] = $this->isAllowed = true;
			return true;
		}

		if (!empty($C->Project->config['private'])) {
			$this->isPublic = false;
		}

		if ($this->check($C) === false) {
			if ($this->isPublic === false) {
				if (!$this->isAllowed && !in_array($this->url, array('users/add', 'users/login', 'users/logout', 'users/account'))) {
					if (!in_array($this->url, array('projects'))) {
						$C->Session->setFlash('Select a Project');
						$C->redirect(array('admin' => false, 'controller' => 'projects'));
					}
				}
				return false;
			}
			if ($this->user($C)) {
				$C->Session->setFlash('You do not have permission to access ' . $this->url);
				$C->redirect($C->referer());
			} else if (!in_array($this->url, array('users/login', 'users/logout'))) {
				$C->Session->setFlash('You do not have permission to access ' . $this->url);
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'login'));
			}
		}

		if ($this->isAllowed) {
			$C->Auth->allow($C->action);
			return true;
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function check(&$C) {
		if ($this->isAllowed) {
			return true;
		}

		$crud = $access = 'r';
		if (!empty($C->Auth->actionMap[$C->params['action']])) {
			$crud = $C->Auth->actionMap[$C->params['action']][0];
		}
		if (in_array($crud, array('c', 'u', 'd'))) {
			$access = 'w';
		}

		$admin = array(
			'user' => $this->user('username'),
			'access' => array($access, $crud),
			'default' => false
		);

		$allowAdmin = $C->Project->Permission->check('admin', $admin);

		if ($allowAdmin === true) {
			return $C->params['isAdmin'] = $this->isAllowed = true;
		}

		$default = ($access === 'w') ? !$this->isPublic : $this->isPublic;

		$user = array(
			'user' => $this->user('username'),
			'access' => array($access, $crud),
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
}