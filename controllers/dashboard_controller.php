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
class DashboardController extends AppController {

	var $name = 'Dashboard';

	var $uses = array();

	function index() {
		$this->Project->bindModel(array('hasMany' => array(
			'Wiki' => array('conditions' => array('Wiki.project_id' => $this->Project->id)),
			'Ticket' => array('conditions' => array('Ticket.project_id' => $this->Project->id)),
			'Commit' => array('conditions' => array('Commit.project_id' => $this->Project->id)),
		)));

		$this->Project->Wiki->recursive = 0;
		$wiki = $this->Project->Wiki->find('all', array(
			'conditions' => array('Wiki.project_id' => $this->Project->id, 'Wiki.active' => 1),
			'limit' => 10, 'order' => 'Wiki.created DESC'
		));

		$this->Project->Ticket->recursive = -1;
		$tickets = $this->Project->Ticket->find('all', array(
			'conditions' => array('Ticket.project_id' => $this->Project->id),
			'limit' => 10, 'order' => 'Ticket.created DESC'
		));

		$this->Project->Ticket->Comment->recursive = 0;
		$comments = $this->Project->Ticket->Comment->find('all', array(
			'conditions' => array('Ticket.project_id' => $this->Project->id),
			'limit' => 10, 'order' => 'Comment.created DESC'
		));

		$this->Project->Commit->recursive = -1;
		$commits = $this->Project->Commit->find('all', array(
			'conditions' => array('Commit.project_id' => $this->Project->id),
			'limit' => 10, 'order' => 'Commit.created DESC'
		));

		$this->set(compact('wiki', 'tickets', 'comments', 'commits'));
	}

	function admin_index() {
		$this->index();
	}

}
?>