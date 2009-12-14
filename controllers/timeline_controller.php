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
class TimelineController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Timeline';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $helpers = array('Time', 'Text');

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
	function index() {
		Router::connectNamed(array('type', 'page'));

		if (empty($this->paginate['conditions'])) {
			$this->paginate['conditions'] = array(
				'Timeline.project_id' => $this->Project->id
			);
		}

		if (!empty($this->passedArgs['type'])) {
			$this->paginate['conditions']['Timeline.model'] = Inflector::classify($this->passedArgs['type']);
		} else if ($this->action !== 'forks'){
			$this->passedArgs['type'] = null;
		}

		$this->Timeline->recursive = 0;
		$this->set('timeline', $this->paginate());

		$this->set('rssFeed', array('controller' => 'timeline'));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function parent() {
		if (!empty($this->Project->current['fork'])) {
			$this->paginate['conditions'] = array(
				'Timeline.project_id' => $this->Project->current['project_id']
			);
		}
		$this->index();
		$this->set('rssFeed', array('controller' => 'timeline', 'action' => 'parent'));

		$this->render('index');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function forks() {
		if (empty($this->Project->current['fork'])) {
			$forks = $this->Project->forks();
			$this->paginate['conditions'] = array(
				'Timeline.project_id' => $forks
			);
		}
		$this->index();
		$this->set('rssFeed', array('controller' => 'timeline', 'action' => 'forks'));

		$this->render('index');
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function remove($id = null) {
		if (!$id || empty($this->params['isAdmin'])) {
			$this->redirect($this->referer());
		}

		if ($this->Timeline->del($id)) {
			$this->Session->setFlash(__('The timeline event was deleted',true));
		} else {
			$this->Session->setFlash(__('The timeline event was NOT deleted',true));
		}
		$this->redirect(array('action' => 'index'));
	}
}
?>