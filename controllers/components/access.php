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
	function initialize(&$controller) {
		$controller->params['isAdmin'] = false;

		if ($controller->name == 'CakeError') {
			return false;
		}

		if (empty($controller->Project)) {
			if ($controller->params['url']['url'] != 'pages/home') {
				$controller->Session->write('Install', true);
				$controller->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			} else {
				$controller->Auth->allow($controller->action);
			}
			return;
		}

		if ($controller->Project->initialize($controller->params) === false) {
			if ($controller->params['url']['url'] == 'users/logout') {
				$controller->Auth->allow($controller->action);
				return;
			}

			if ($controller->params['url']['url'] != 'pages/home' && !$controller->Session->read('Install')) {
				$controller->Session->write('Install', true);
				$controller->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			}

			if (in_array($controller->params['url']['url'], array('pages/home', 'users/add', 'users/login'))) {
				$controller->Auth->allow($controller->action);
				return;
			}

			if ($controller->Auth->user()) {
				if (in_array($controller->params['url']['url'], array('install', 'admin/projects/add'))) {
					$controller->params['isAdmin'] = true;
					$controller->Auth->allow($controller->action);
				} else {
					$controller->Session->setFlash('Chaw needs to be installed');
					$controller->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
				}
			} else {
				if (in_array($controller->params['url']['url'], array('install', 'admin/projects/add'))) {
					$login =  Router::url(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'login'));
					$controller->Session->setFlash("Chaw needs to be installed. Please <a href='{$login}'>Login</a> or Register");
					$controller->redirect(array('admin' => false, 'project' => null, 'controller' => 'users', 'action'=> 'add'));
				}
				$controller->Session->setFlash('Chaw needs to be installed');
				$controller->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			}
		} else {

			if ($this->check($controller) === false) {
				if ($controller->Auth->user()) {
					$controller->Session->setFlash('You are not authorized to access that location');
					$controller->redirect($controller->referer());
				}
				if ($this->isAllowed && $this->isPublic === false) {
					$controller->Session->setFlash('Select a Project');
					$controller->redirect(array('controller' => 'projects'));
				}
			}
			if ($this->isAllowed) {
				$controller->Auth->allow($controller->action);
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
	function check(&$controller) {

		$isOwner = ($controller->Auth->user('id') == $controller->Project->config['user_id']);
		if ($isOwner) {
			$controller->params['isAdmin'] = $this->isAllowed = true;
			return true;
		}

		if (!empty($controller->Project->config['private'])) {
			$this->isPublic = false;
		}

		$crud = $access = 'r';
		if (!empty($controller->Auth->actionMap[$controller->params['action']])) {
			$crud = $controller->Auth->actionMap[$controller->params['action']][0];
		}
		if (in_array($crud, array('c', 'u', 'd'))) {
			$access = 'w';
		}

		$this->isAllowed = (
			in_array($controller->params['url']['url'], array('users/add', 'projects')) ||
			in_array($controller->action, $controller->Auth->allowedActions)
		);

		if ($this->isAllowed) {
			return true;
		}

		$admin = array(
			'user' => $controller->Auth->user('username'),
			'access' => array($access, $crud),
			'default' => false
		);

		$allowed = $controller->Project->Permission->check('admin', $admin);

		if ($allowed === true) {
			$controller->params['isAdmin'] = $this->isAllowed = true;
			return true;
		}

		$user = array(
			'user' => $controller->Auth->user('username'),
			'access' => array($access, $crud),
			'default' => $this->isPublic
		);

		$allowed = $controller->Project->Permission->check($controller->params['controller'], $user);

		if ($allowed === true) {
			return $this->isAllowed = true;
		}

		return false;
	}
}