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
class DashboardController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Dashboard';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Timeline');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $helpers = array('Text');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $paginate = array(
		'limit' => 20,
		'order' => 'Timeline.created DESC, Timeline.id DESC'
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Access->allow('index', 'feed');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		Router::connectNamed(array('page', 'type'));

		$this->set('rssFeed', array('controller' => 'dashboard', 'action' => 'feed'));

		extract($this->Project->User->projects($this->Auth->user('id')));

		if (empty($ids)) {
			return;
		}

		$this->paginate['conditions']['Timeline.project_id'] = $ids;

		if (!empty($this->passedArgs['type'])) {
			$this->paginate['conditions']['Timeline.model'] = Inflector::classify($this->passedArgs['type']);
		} else if ($this->action !== 'forks'){
			$this->passedArgs['type'] = null;
		}

		$this->set('timeline', $this->paginate('Timeline'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function feed() {
		$this->set('rssFeed', array('controller' => 'dashboard', 'action' => 'feed'));

		extract($this->Project->User->projects($this->Auth->user('id')));

		if (empty($ids)) {
			return;
		}

		$this->paginate['conditions']['Timeline.project_id'] = $ids;

		if (!empty($this->passedArgs['type'])) {
			$this->paginate['conditions']['Timeline.model'] = Inflector::classify($this->passedArgs['type']);
		}

		$this->set('feed', $this->paginate('Timeline'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_index() {
		Router::connectNamed(array('page', 'type'));

		$projects = $this->Project->forks();
		array_unshift($projects, $this->Project->id);

		$this->paginate['conditions']['Timeline.project_id'] = $projects;

		if (!empty($this->passedArgs['type'])) {
			$this->paginate['conditions']['Timeline.model'] = Inflector::classify($this->passedArgs['type']);
		} else if ($this->action !== 'forks'){
			$this->passedArgs['type'] = null;
		}

		$this->set('timeline', $this->paginate('Timeline'));
	}

}
?>