<?php
/* SVN FILE: $Id: app_controller.php 6296 2008-01-01 22:18:17Z phpnut $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 6296 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 14:18:17 -0800 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */
class AppController extends Controller {

	var $components = array('Auth');

	var $helpers = array(
		'Html', 'Form', 'Javascript'
	);

	var $uses = array('Project');

	function beforeFilter() {

		if ($this->Project->initialize($this->params) === false && $this->here !== $this->base . '/install' && empty($this->data['Project'])) {
			$this->redirect(array('admin' => false, 'project' => null, 'controller' => 'install'));
		}
		
		if (!empty($this->params['project']) && $this->Project->id == 1) {
			unset($this->params['project']);
		}

		if ($this->here === $this->base . '/install' || $this->here === $this->base . '/admin/projects/add') {
			$this->Auth->allow($this->action);
		}

		if ($this->action == 'admin_login') {
			$this->params['action'] = $this->action = 'login';
		}

		$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
		$this->Auth->allow('index', 'view');
	}

	function beforeRender() {
		if (!empty($this->params['admin'])) {
			$this->layout = 'admin';
		}

		$this->set('CurrentUser', Set::map($this->Auth->user()));
	}

	function appError($method, $messages) {
		pr($this->params);
		pr($method);
		if (($method == 'missingAction' || $method == 'missingController') && $this->here !== $this->base . '/admin/install') {
			pr($method);
			pr($messages);
			//$this->redirect(array('controller' => 'wiki', 'action' => $messages[0]['url'], $this->passedArgs));
		}

		//
		// return new ErrorHandler($method, $messages);
	}

	function redirect($url = array(), $status = null, $exit = true) {
		if (is_array($url) && !empty($this->params['project'])) {
			$url = array_merge(array('project' => $this->params['project']), $url);
		}
		parent::redirect($url, $status, $exit);
	}
}
?>