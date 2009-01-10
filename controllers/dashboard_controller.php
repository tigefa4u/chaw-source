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

	function beforeFilter() {
		parent::beforeFilter();
		$this->Access->allow('index', 'feed');
	}

	function index() {
		extract($this->Project->User->projects($this->Auth->user('id')));

		if (empty($ids)) {
			return;
		}
		$wiki = $this->Timeline->Wiki->find('all', array(
			'conditions' => array('Wiki.project_id' => $ids, 'Wiki.active' => 1),
			'limit' => 10, 'order' => 'Wiki.created DESC',
			'recursive' => 0
		));

		$tickets = $this->Timeline->Ticket->find('all', array(
			'conditions' => array('Ticket.project_id' => $ids),
			'limit' => 10, 'order' => 'Ticket.created DESC',
			'recursive' => 0
		));

		$this->Timeline->Ticket->Comment->User->unbindModel(array(
			'hasOne' => array('Permission'),
		));

		$comments = $this->Timeline->Ticket->Comment->find('all', array(
			'conditions' => array('Ticket.project_id' => $ids),
			'limit' => 10, 'order' => 'Comment.created DESC',
			'recursive' => 2
		));

		$commits = $this->Timeline->Commit->find('all', array(
			'conditions' => array('Commit.project_id' => $ids),
			'limit' => 10, 'order' => 'Commit.created DESC',
			'recursive' => 0
		));
		
		$this->set(compact('projects', 'wiki', 'tickets', 'comments', 'commits'));
		
		$this->set('rssFeed', array('controller' => 'dashboard', 'action' => 'feed'));
	}

	function feed() {
		extract($this->Project->User->projects($this->Auth->user('id')));

		if (empty($ids)) {
			return;
		}

		$this->paginate['order'] = 'Timeline.created DESC';

		if (empty($this->paginate['conditions'])) {
			$this->paginate['conditions'] = array(
				'Timeline.project_id' => $ids
			);
		}

		if (!empty($this->passedArgs['type'])) {
			$this->paginate['conditions']['Timeline.model'] = Inflector::classify($this->passedArgs['type']);
		} else if ($this->action !== 'forks'){
			$this->passedArgs['type'] = null;
		}

		$this->Timeline->recursive = -1;
		$data = $this->paginate();

		$this->set('feed', $this->Timeline->related($data));
		
		$this->helpers[] = 'Text';
		$this->set('rssFeed', array('controller' => 'dashboard', 'action' => 'feed'));
	}

	function admin_index() {
		$this->Project->bindModel(array('hasMany' => array(
			'Wiki' => array('conditions' => array('Wiki.project_id' => $this->Project->id)),
			'Ticket' => array('conditions' => array('Ticket.project_id' => $this->Project->id)),
			'Commit' => array('conditions' => array('Commit.project_id' => $this->Project->id)),
		)));

		$wiki = $this->Project->Wiki->find('all', array(
			'conditions' => array('Wiki.project_id' => $this->Project->id, 'Wiki.active' => 1),
			'limit' => 10, 'order' => 'Wiki.created DESC',
			'recursive' => 0
		));

		$tickets = $this->Project->Ticket->find('all', array(
			'conditions' => array('Ticket.project_id' => $this->Project->id),
			'limit' => 10, 'order' => 'Ticket.created DESC',
			'recursive' => -1
		));

		$comments = $this->Project->Ticket->Comment->find('all', array(
			'conditions' => array('Ticket.project_id' => $this->Project->id),
			'limit' => 10, 'order' => 'Comment.created DESC',
			'recursive' => 0
		));

		$commits = $this->Project->Commit->find('all', array(
			'conditions' => array('Commit.project_id' => $this->Project->id),
			'limit' => 10, 'order' => 'Commit.created DESC',
			'recursive' => 0
		));

		$forkCommits = null;
		if (empty($this->Project->config['fork'])) {
			$forks = $this->Project->forks();
			$forkCommits = $this->Project->Commit->find('all', array(
				'conditions' => array('Commit.project_id' => $forks),
				'limit' => 10, 'order' => 'Commit.created DESC',
				'recursive' => 0
			));
		}

		$this->set(compact('wiki', 'tickets', 'comments', 'commits', 'forkCommits'));
	}

}
?>