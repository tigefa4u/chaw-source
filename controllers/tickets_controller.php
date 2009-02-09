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
class TicketsController extends AppController {

	var $name = 'Tickets';

	var $helpers = array('Time');

	var $paginate = array('order' => 'Ticket.number DESC');

	function index() {
		Router::connectNamed(array('status', 'page', 'user', 'priority'));
		$searchKeys = array('type', 'priority');

		if (count($this->params['url']) > 2) {
			die(Router::url($this->_toNamedParams($searchKeys) + $this->params['named']));
			$this->redirect();
		}

		foreach ($searchKeys as $key) {
			if (isset($this->params['named'][$key])) {
				$this->data['Ticket'][$key] = explode(',', $this->params['named'][$key]);
			}
		}

		$statuses = array_values($this->Project->ticket('statuses'));
		$current = $statuses[0];

		if (!empty($this->passedArgs['status'])) {
			$current = $this->passedArgs['status'];
		}

		$conditions = array('Ticket.project_id' => $this->Project->id);
		$conditions['Ticket.status'] = $current;

		if (!empty($this->passedArgs['user'])) {
			$current = $this->passedArgs['user'];
			$conditions['Owner.username'] = $this->passedArgs['user'];
		}
		
		/*
		if (!empty($this->Project->config['fork'])) {
			$conditions = array('OR' => array(
				array('Ticket.project_id' => $this->Project->id),
				array('Ticket.project_id' => $this->Project->config['project_id'])
			));
		}
		*/
		
		$this->pageTitle = 'Tickets/Status/' . Inflector::humanize($current);
		$tickets = $this->paginate('Ticket', $conditions);

		$this->Session->write('Ticket.back', '/' . $this->params['url']['url']);
		$this->set(compact('current', 'tickets'));
		$this->_ticketInfo();
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid ticket',true));
			$this->redirect(array('controller'=> 'tickets', 'action' => 'index'));
		}

		$this->Ticket->contain(array('Reporter', 'Owner', 'Tag', 'Comment', 'Comment.User'));
		$ticket = $this->data = $this->Ticket->find(array(
			'Ticket.number' => $id,
			'Ticket.project_id' => $this->Project->id
		));

		if (empty($ticket)) {
			$this->Session->setFlash(__('Invalid ticket',true));
			$this->redirect(array('controller'=> 'tickets', 'action' => 'index'));
		}

		$this->data['Ticket']['tags'] = $this->Ticket->Tag->toString($this->data['Tag']);
		$owners = $this->Project->users();

		$this->Session->write('Ticket.previous', $this->data['Ticket']);
		$this->set(compact('ticket', 'owners'));
		$this->_ticketInfo();
	}

	/**
	 * Creates a new ticket
	 *
	 * @todo Automatically move the ticket status to 'approved' if the user has ticket
	 *       permissions on the project.  This should probably be implemented in the Ticket model.
	 * @return void
	 */
	function add() {
		if (!empty($this->data)) {
			$init = array(
				'reporter' => $this->Auth->user('id'),
				'project_id' => $this->Project->id
			);
			$this->Ticket->create($this->data);

			if ($this->Ticket->save($init)) {
				$this->Session->setFlash(__('Ticket saved', true));
				$this->redirect(array('controller'=> 'tickets', 'action' => 'index'));
			}
		}

		$owners = $this->Project->users();
		$this->set(compact('owners'));
		$this->_ticketInfo();
	}

	function modify($id = null) {
		if (empty($this->params['form']['cancel']) && !empty($this->data)) {

			$this->Ticket->set(array(
				'user_id' => $this->Auth->user('id'),
				'project_id' => $this->Project->id,
				'previous' => $this->Session->read('Ticket.previous')
			));

			if ($data = $this->Ticket->save($this->data)) {
				if (!empty($data['Ticket']['comment'])) {
					$this->Session->setFlash(__('Comment saved',true));
				} else {
					$this->Session->setFlash(__('Ticket updated',true));
				}
				$this->Session->del('Ticket.previous');
			} else {
				if (!empty($data['Ticket']['comment'])) {
					$this->Session->setFlash(__('Comment was NOT saved',true));
				} else {
					$this->Session->setFlash(__('Ticket was NOT updated',true));
				}
			}
		}

		$this->redirect(array('action' => 'view', $id));
	}

	function _ticketInfo() {
		$versions = $this->Ticket->Version->find('list', array(
			'conditions' => array('Version.project_id' => $this->Project->id
		)));
		$types = $this->Project->ticket('types');
		$statuses = $this->Project->ticket('statuses');
		$priorities = $this->Project->ticket('priorities');

		$this->set(compact('versions', 'types', 'statuses', 'priorities'));
	}
}

?>