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

	var $components = array('Auth', 'Access');

	var $helpers = array(
		'Html', 'Form', 'Javascript', 'Admin'
	);

	var $uses = array('Project');
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function beforeFilter() {

		if (!empty($this->params['project']) && $this->Project->id == 1) {
			unset($this->params['project']);
		}

		if ($this->action == 'admin_login') {
			$this->params['action'] = $this->action = 'login';
		}

		$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function beforeRender() {
		if (!empty($this->params['admin'])) {
			$this->layout = 'admin';
		}
		$this->set('CurrentUser', Set::map($this->Auth->user()));
		$this->set('CurrentProject', Set::map(Configure::read('Project')));
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function redirect($url = array(), $status = null, $exit = true) {
		if (is_array($url) && !empty($this->params['project'])) {
			$url = array_merge(array('project' => $this->params['project']), $url);
		}
		parent::redirect($url, $status, $exit);
	}
}
?>