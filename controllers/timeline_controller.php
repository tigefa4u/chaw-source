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
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class TimelineController extends AppController {

	var $name = 'Timeline';

	var $helpers = array('Time');

	var $paginate = array(
		'limit' => 30,
		'order' => 'Timeline.created DESC',
		'contain' => array(
			'Comment', 'Comment.User', 'Comment.Ticket', 'Commit', 'Commit.User', 'Ticket', 'Ticket.Reporter',
			'Wiki', 'Wiki.User'
		),
	);

	function index() {
		Router::connectNamed(array('type', 'page'));

		$this->Timeline->recursive = 2;

		$this->paginate['conditions'] = array('Timeline.project_id' => $this->Project->id);

		if (!empty($this->passedArgs['type'])) {
			$this->paginate['conditions']['Timeline.model'] = Inflector::classify($this->passedArgs['type']);
		}

		$timeline = $this->paginate();
		$this->set('timeline', $timeline);

		$this->set('rssFeed', array('controller' => 'timeline'));
	}

	function sync() {

	}

	function delete($id = null) {
		if (!$id) {
			$this->redirect($this->referer());
		}

		if ($this->Timeline->del($id)) {
			$this->Session->setFlash('The timeline event was deleted');
		} else {
			$this->Session->setFlash('The timeline event was NOT deleted');
		}
		$this->redirect(array('action' => 'index'));
	}
}
?>