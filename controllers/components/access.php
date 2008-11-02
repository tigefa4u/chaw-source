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

	var $isAllowed = false;

	var $isPublic = true;
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function initialize(&$C) {
		$C->params['isAdmin'] = false;

		if ($C->name == 'CakeError') {
			return $this->enabled = false;
		}

		if (empty($C->Project)) {
			if ($C->params['url']['url'] != 'pages/home') {
				$C->Session->write('Install', true);
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			} else {
				$C->Auth->allow($C->action);
			}
			return;
		}

		if ($C->Project->initialize($C->params) === false) {
			if ($C->params['url']['url'] == 'users/logout') {
				$C->Auth->allow($C->action);
				return;
			}

			if ($C->params['url']['url'] != 'pages/home' && !$C->Session->read('Install')) {
				$C->Session->write('Install', true);
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			}

			if (in_array($C->params['url']['url'], array('pages/home', 'users/add', 'users/login'))) {
				$C->Auth->allow($C->action);
				return;
			}

			if ($C->Auth->user()) {
				if (in_array($C->params['url']['url'], array('install', 'admin/projects/add'))) {
					$C->params['isAdmin'] = true;
					$C->Auth->allow($C->action);
				} else {
					$C->Session->setFlash('Chaw needs to be installed');
					$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
				}
			} else {
				if (in_array($C->params['url']['url'], array('install', 'admin/projects/add'))) {
					$login =  Router::url(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'login'));
					$C->Session->setFlash("Chaw needs to be installed. Please <a href='{$login}'>Login</a> or Register");
					$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'add'));
				}
				$C->Session->setFlash('Chaw needs to be installed');
				$C->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			}
		} else {

			if ($this->check($C) === false) {
				if ($C->Auth->user()) {
					$C->Session->setFlash('You are not authorized to access that location');
					$C->redirect($C->referer());
				}
				if ($this->isAllowed && $this->isPublic === false) {
					$C->Session->setFlash('Select a Project');
					$C->redirect(array('controller' => 'projects'));
				}
			}
			if ($this->isAllowed) {
				$C->Auth->allow($C->action);
				return true;
			}
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function check(&$C) {

		$isOwner = ($C->Auth->user('id') == $C->Project->config['user_id']);
		if ($isOwner) {
			$C->params['isAdmin'] = $this->isAllowed = true;
			return true;
		}

		if (!empty($C->Project->config['private'])) {
			$this->isPublic = false;
		}

		$crud = $access = 'r';
		if (!empty($C->Auth->actionMap[$C->params['action']])) {
			$crud = $C->Auth->actionMap[$C->params['action']][0];
		}
		if (in_array($crud, array('c', 'u', 'd'))) {
			$access = 'w';
		}

		$this->isAllowed = (
			in_array($C->params['url']['url'], array('projects', 'users/add', 'users/login', 'users/logout')) ||
			in_array($C->action, $C->Auth->allowedActions)
		);

		if ($this->isAllowed) {
			return true;
		}

		$admin = array(
			'user' => $C->Auth->user('username'),
			'access' => array($access, $crud),
			'default' => false
		);

		$allowed = $C->Project->Permission->check('admin', $admin);

		if ($allowed === true) {
			$C->params['isAdmin'] = $this->isAllowed = true; 
			return true;
		}

		$default = ($access === 'w') ? !$this->isPublic : $this->isPublic;

		$user = array(
			'user' => $C->Auth->user('username'),
			'access' => array($access, $crud),
			'default' => $default
		);

		$allowed = $C->Project->Permission->check($C->params['controller'], $user);

		if ($allowed === true) {
			return $this->isAllowed = true;
		}

		return false;
	}
}