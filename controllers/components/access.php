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
 * @subpackage		chaw.controllers.components
 * @since			Chaw 0.1
 * @license			commercial
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

		if ($C->name == 'CakeError') {
			return $this->enabled = false;
		}

		$this->isAllowed = false;
		$this->isPublic = true;

		$this->url = $C->params['url']['url'];

		if (empty($this->user)) {
			$this->user = $C->Auth->user();
		}

		if ($this->url === 'start') {
			$C->Session->write('Install', true);
			$C->Auth->allow('start');
			$this->allow($C->action);
			return true;
		}

		if (empty($C->Project)) {
			$this->allow($C->action);
			$C->Auth->allow($C->action);
			return true;
		}

		if ($C->Project->initialize($C->params) === false) {
			if ($C->Session->read('Install') !== true) {
				$C->Session->setFlash(__('Chaw needs to be installed',true));
				$C->redirect(array(
					'admin' => false, 'project' => null,
					'controller' => 'pages', 'action'=> 'start'
				));
				return false;
			}

			if ($this->user()) {
				if (!in_array($this->url, array('projects/add'))) {
					$C->Session->setFlash(__('Chaw needs to be installed',true));
					$C->redirect(array(
						'admin' => false, 'project' => false,
						'controller' => 'pages', 'action'=> 'start'
					));
					return false;
				}
				$C->params['isAdmin'] = true;
				return true;
			}

			if (!in_array($this->url, array('users/add', 'users/login'))) {
				$login =  Router::url(array(
					'admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'login'
				));
				$C->Session->setFlash(sprintf(__("Chaw needs to be installed. Please <a href='%s'>Login</a> or Register",true),$login));
				$C->redirect(array(
					'admin' => false, 'project' => null,
					'controller' => 'users', 'action'=> 'add'
				));
				return false;
			}
			$this->allow($C->action);
			$C->Auth->allow('add');
			return true;
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function startup(&$C) {
		if (!empty($_COOKIE['Chaw']) && empty($this->user) && ($C->action !== 'login' || $C->action !== 'logout')) {
			$C->Session->write('Access.redirect', '/' . ltrim($this->url, '/'));
			$C->redirect('/users/login');
		}

		$this->isAllowed = in_array($C->action, $this->allowedActions);

		if (!empty($C->Project->config) && $this->user('id') == $C->Project->config['user_id']) {
			return $C->params['isOwner'] = $C->params['isAdmin'] = true;
		}

		if (!empty($C->Project->config['private'])) {
			$this->isPublic = false;
		}

		if ($this->isAllowed) {
			return true;
		}

		$this->access = 'r';
		if (!empty($C->Auth->actionMap[$C->action])) {
			$this->access = $C->Auth->actionMap[$C->action][0];
		}

		$loginRequired = (empty($this->user) && (!empty($C->params['admin']) || $this->access !== 'r'));

		if ($loginRequired) {
			$C->Auth->deny($C->action);
			$C->Auth->authError = "Please login to continue.";
			return false;
		}

		if ($this->check($C, array('admin' => true))) {
			if ($C->Auth->authorize == false) {
				$C->Auth->allow($C->action);
				return true;
			}
		}

		if ($this->isPublic === false) {
			$C->Auth->deny($C->action);
			if (!$this->user()) {
				$C->Session->setFlash(__('Select a Project',true));
				$C->redirect(array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'projects', 'action' => 'index'
				));
				return false;
			}
		}

		if ($C->Auth->authorize == false) {
			$referer = $C->referer();
			if ($referer == '/' || strpos($referer, 'login') !== false) {
				$referer = array(
					'admin' => false, 'project' => false, 'fork' => false,
					'controller' => 'dashboard', 'action' => 'index'
				);
			}
			$C->Session->setFlash($C->Auth->authError);
			$C->redirect($referer);
		}
		return false;
	}
/**
 * Check access against permissions
 *
 * @param array options
 *  username, action, access, admin, default
 * @return void
 *
 **/
	function check(&$C, $options = array()) {
		extract(array_merge(array(
			'username' => $this->user('username'),
			'path' => (!empty($C->params['controller'])) ? $C->params['controller'] : false,
			'access' => $this->access,
			'admin' => false,
			'default' => $this->isPublic
		), $options));

		if (empty($this->user) && $access !== 'r') {
			return false;
		}

		$group = $this->user("Permission.{$C->Project->id}");

		if ($username && $admin === true) {
			$admin = array(
				'group' => $group,
				'user' => $username,
				'access' => $access,
				'default' => false
			);

			$allowAdmin = $C->Project->Permission->check('admin', $admin);

			if ($allowAdmin === true) {
				return $C->params['isAdmin'] = true;
			}
		}

		if ($path) {
			$user = array(
				'group' => $group,
				'user' => $username,
				'access' => $access,
				'default' => $default
			);

			$allowUser = $C->Project->Permission->check($path, $user);

			if ($allowUser === true) {
				return true;
			}
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
			if (!empty($this->user['User'][$key][$field])) {
				return $this->user['User'][$key][$field];
			}
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