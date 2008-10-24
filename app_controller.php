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
 * @subpackage		chaw
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
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

		if ($this->action == 'admin_login') {
			$this->params['action'] = $this->action = 'login';
		}

		if ($this->here === $this->base . '/install' || $this->here === $this->base . '/admin/projects/add') {
			$this->Auth->allow($this->action);
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

	function appErrorTwo($method, $messages) {
		pr($this->params);
		pr($method);
		if (($method == 'missingAction' || $method == 'missingController') && $this->here !== $this->base . '/admin/install') {
			pr($method);
			pr($messages);
			//$this->redirect(array('controller' => 'wiki', 'action' => $messages[0]['url'], $this->passedArgs));
		} else {
			$this->layout = 'error';
			$this->set($messages[0]);
			echo $this->render('/errors/' . Inflector::underscore($method));
			$this->_stop();
		}
	}


	function redirect($url = array(), $status = null, $exit = true) {
		if (is_array($url) && !empty($this->params['project'])) {
			$url = array_merge(array('project' => $this->params['project']), $url);
		}
		parent::redirect($url, $status, $exit);
	}
}
?>