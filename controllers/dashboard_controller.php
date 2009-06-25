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
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class DashboardController extends AppController {

	var $name = 'Dashboard';

	var $uses = array('Timeline');

	var $helpers = array('Text');

	var $paginate = array(
		'limit' => 20,
		'order' => 'Timeline.created DESC, Timeline.id DESC'
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Access->allow('index', 'feed');
	}

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