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
 * @subpackage		chaw
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class AppController extends Controller {

	var $components = array('Access', 'Auth', 'RequestHandler', /*'DebugKit.Toolbar'*/);

	var $helpers = array('Html', 'Form', 'Javascript', 'Chaw');

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
		if (!empty($this->params['admin'])) {
			$this->layout = 'admin';
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
		$this->set('CurrentProject', Set::map($this->Project->current, true));
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

	/**
	 * Converts the $_GET parameters specified in $params to an array suitable for use in named
	 * parameters.  If a key is an array of values, those values will be compacted into a comma-
	 * separated string.
	 *
	 * @param array $params The keys of GET parameters (from $this->params['url']) to convert.
	 * @param array $get Optional.  If specified, replaces $this->params['url'] as the source for
	 *              GET data.
	 * @return array A 1-depth of all the keys in $params which are present in $this->params['url'].
	 */
	function _toNamedParams($params, $get = null) {
		$named = array();
		$get = is_null($get) ? $this->params['url'] : $get;

		foreach ($params as $key) {
			if (isset($get[$key])) {
				$named[$key] = join(',', (array)$get[$key]);
			}
		}
		return $named;
	}
}

?>