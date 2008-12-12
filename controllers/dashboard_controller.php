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
		$this->Access->allow('index');
	}

	function index() {
		if (!$this->Auth->user()) {
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$user = $this->Auth->user('id');

		$projects = $this->Project->Permission->find('all', array(
			'conditions' => array('Permission.user_id' => $user, 'Project.name !=' => null)
		));
		$this->set('projects', $projects);

		$ids = array_filter(Set::extract($projects, '/Project/id'));

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

		$this->set(compact('wiki', 'tickets', 'comments', 'commits'));

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

		$this->set(compact('wiki', 'tickets', 'comments', 'commits'));
	}

}
?>