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
class TimelineController extends AppController {

	var $name = 'Timeline';

	var $helpers = array('Time', 'Text');

	var $paginate = array(
		'limit' => 20,
		'order' => 'Timeline.created DESC, Timeline.id DESC'
	);

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

		$this->Timeline->recursive = -1;
		$timeline = $this->paginate();

		$this->set('timeline', $this->Timeline->related($timeline));

		$this->set('rssFeed', array('controller' => 'timeline'));
	}

	function parent() {
		if (!empty($this->Project->config['fork'])) {
			$this->paginate['conditions'] = array(
				'Timeline.project_id' => $this->Project->config['project_id']
			);
		}
		$this->index();
		$this->set('rssFeed', array('controller' => 'timeline', 'action' => 'parent'));

		$this->render('index');
	}

	function forks() {
		if (empty($this->Project->config['fork'])) {
			$forks = $this->Project->forks();
			$this->paginate['conditions'] = array(
				'Timeline.project_id' => $forks
			);
		}
		$this->index();
		$this->set('rssFeed', array('controller' => 'timeline', 'action' => 'forks'));

		$this->render('index');
	}

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