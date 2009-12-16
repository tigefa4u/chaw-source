<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
 */
class AppController extends Controller {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $theme = null;

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $view = 'Theme';
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $components = array('Access', 'Auth', 'RequestHandler', /*'DebugKit.Toolbar'*/);

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $helpers = array('Html', 'Form', 'Chaw');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project');

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeFilter() {
		$this->Auth->loginAction = '/users/login';
		$this->Auth->mapActions(array(
			'modify' => 'update',
			'remove' => 'delete'
		));

		if (!empty($this->params['admin'])) {
			$this->Auth->authorize = 'controller';
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function isAuthorized() {
		if (!empty($this->params['admin']) && empty($this->params['isAdmin'])) {
			if ($this->Access->check($this, array('access' => 'w', 'default' => false)) === true) {
				return true;
			}
			$this->Session->setFlash($this->Auth->authError, 'default', array(), 'auth');
			$this->redirect(array(
				'admin' => false, 'project' => false, 'fork' => false,
				'controller' => 'dashboard', 'action' => 'index'
			));
			return false;
		}
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeRender() {
		if ($this->params['isAdmin'] !== true) {
			$this->params['admin'] = false;
		}

		$this->params['project'] = null;
		if (!empty($this->Project->current) && $this->Project->id !== '1') {
			$this->params['project'] = $this->Project->current['url'];
		}

		if (isset($this->viewVars['rssFeed'])) {
			$this->viewVars['rssFeed'] = array_merge(
				array(
				'controller' => 'timeline', 'action' => 'index', 'ext' => 'rss'
				),
				$this->viewVars['rssFeed']
			);
		}

		$this->set('CurrentUser', Set::map($this->Auth->user()));
		$this->set('CurrentProject', Set::map(Configure::read('Project'), true));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function redirect($url = array(), $status = null, $exit = true) {
		if (is_array($url)) {
			if (!empty($this->params['project'])) {
				$url = array_merge(array('project' => $this->params['project']), $url);
			}
			if (!empty($this->params['fork'])) {
				$url = array_merge(array('fork' => $this->params['fork']), $url);
			}
		}
		parent::redirect($url, $status, $exit);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function referer($default = null, $local = true) {
		return parent::referer($default, $local);
	}
}

?>