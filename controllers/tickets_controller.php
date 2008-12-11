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
class TicketsController extends AppController {

	var $name = 'Tickets';

	var $helpers = array('Time');

	function index() {
		Router::connectNamed(array('status', 'page'));

		$this->Ticket->recursive = 0;
		$statuses = array_values($this->Project->ticket('statuses'));

		$conditions = array('Ticket.project_id' => $this->Project->id);

		if (empty($this->passedArgs['status'])) {
			$this->passedArgs['status'] = $statuses[0];
		}
		$conditions['Ticket.status'] = $this->passedArgs['status'];

		$this->set('current', $this->passedArgs['status']);
		$this->set('statuses', $statuses);
		$this->set('tickets', $this->paginate('Ticket', $conditions));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid ticket');
			$this->redirect(array('controller'=> 'tickets', 'action' => 'index'));
		}

		$this->Ticket->contain(array('Reporter', 'Tag', 'Comment', 'Comment.User'));
		$ticket = $this->data = $this->Ticket->find(array(
			'Ticket.number' => $id,
			'Ticket.project_id' => $this->Project->id
		));

		if (empty($ticket)) {
			$this->Session->setFlash('Invalid ticket');
			$this->redirect(array('controller'=> 'tickets', 'action' => 'index'));
		}

		$this->data['Ticket']['tags'] = $this->Ticket->Tag->toString($this->data['Tag']);
		$this->Session->write('Ticket.previous', $this->data['Ticket']);

		$versions = $this->Ticket->Version->find('list', array(
			'conditions' => array('Version.project_id' => $this->Project->id
		)));
		$types = $this->Project->ticket('types');
		$statuses = $this->Project->ticket('statuses');
		$priorities = $this->Project->ticket('priorities');

		$this->set(compact('ticket', 'versions', 'types', 'statuses', 'priorities'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Ticket->create(array(
				'reporter' => $this->Auth->user('id'),
				'project_id' => $this->Project->id,
				'status' => 'open'
			));

			if ($this->Ticket->save($this->data)) {
				$this->Session->setFlash('Ticket saved');
				$this->redirect(array('controller'=> 'tickets', 'action' => 'index'));
			}
		}

		$versions = $this->Ticket->Version->find('list', array(
			'conditions' => array('Version.project_id' => $this->Project->id
		)));
		$types = $this->Project->ticket('types');
		$priorities = $this->Project->ticket('priorities');

		$this->set(compact('versions', 'types', 'priorities'));
	}

	function modify($id = null) {

		if (!empty($this->data)) {

			$this->Ticket->set(array(
				'user_id' => $this->Auth->user('id'),
				'project_id' => $this->Project->id,
				'previous' => $this->Session->read('Ticket.previous')
			));

			if ($data = $this->Ticket->save($this->data)) {
				if (!empty($data['Ticket']['comment'])) {
					$this->Session->setFlash('Comment saved');
				} else {
					$this->Session->setFlash('Ticket updated');
				}
				$this->Session->delete('Ticket.previous');
			} else {
				if (!empty($data['Ticket']['comment'])) {
					$this->Session->setFlash('Comment was NOT saved');
				} else {
					$this->Session->setFlash('Ticket was NOT updated');
				}
			}
		}

		$this->view($id);
		$this->render('view');
	}
}
?>