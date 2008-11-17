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

	var $components = array('Access', 'Auth');

	var $helpers = array(
		'Html', 'Form', 'Javascript', 'Chaw'
	);

	var $uses = array('Project');
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function beforeFilter() {
		$this->Auth->loginAction = '/users/login';
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function beforeRender() {
		if ($this->params['isAdmin'] !== true) {
			$this->params['admin'] = false;
		}
		if (!empty($this->params['admin'])) {
			$this->layout = 'admin';
		}

		$this->params['project'] = null;
		if (!empty($this->Project->config) && $this->Project->id != 1) {
			$this->params['project'] = $this->Project->config['url'];
		}

		$this->set('CurrentUser', Set::map($this->Auth->user()));
		$this->set('CurrentProject', Set::map($this->Project->config, true));
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