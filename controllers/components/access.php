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
 * undocumented function
 *
 * @return void
 *
 **/
	function initialize(&$controller) {
		if(empty($controller->Project)) {
			$controller->Auth->allow($controller->action);
		}
		if (!empty($controller->Project) && $controller->Project->initialize($controller->params) === false) {
			if (in_array($controller->params['url']['url'], array('install', 'admin/projects/add', 'pages/home'))) {
				$controller->Auth->allow($controller->action);
			} else {
				$controller->Session->setFlash('Chaw needs to be installated');
				$controller->redirect(array('admin' => false, 'project' => null, 'controller' => 'pages', 'action'=> 'display', 'home'));
			}
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function startup(&$controller) {
		if (!$controller->Auth->user()) {
			return false;
		}

		$controller->params['isAdmin'] = false;

		$allowed = $controller->Project->Permission->check('admin', array('user' => $controller->Auth->user('username'), 'access' => 'rw'));

		if ($allowed === false && !empty($controller->Project->config['private']) && $controller->name !== 'Projects') {
			if ($controller->params['url']['url'] !== '/') {
				$controller->Session->setFlash('You are not authorized to access that location');
			}
			$controller->redirect(array('controller' => 'projects'));
		} else if ($allowed === true) {
			$controller->params['isAdmin'] = true;
			return true;
		}


		$access = 'r';
		if (!empty($controller->Auth->actionMap[$controller->params['action']])) {
			$access = $controller->Auth->actionMap[$controller->params['action']][0];
		}

		$allowed = $controller->Project->Permission->check($controller->params['controller'], array('user' => $controller->Auth->user('username'), 'access' => $access));
		if ($allowed === true) {
			return true;
		}
		if (in_array($access, array('c', 'u', 'd'))) {
			$access = 'w';
		}

		$allowed = $controller->Project->Permission->check($controller->params['controller'], array('user' => $controller->Auth->user('username'), 'access' => $access));
		if ($allowed === true) {
			return true;
		}

		$controller->Session->setFlash('You are not authorized to access that location');
		$controller->redirect($controller->referer());
		return false;
	}
}