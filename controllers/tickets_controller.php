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

	var $paginate = array('order' => array('Ticket.number' => 'desc'));

	var $components = array(
		'Gpr' => array(
			'keys' => array('type', 'priority'),
			'connect' => array('status', 'page', 'user', 'type', 'priority'),
			'actions' => array('index')
		)
	);

	function index() {
		$conditions = array(
			'Ticket.project_id' => $this->Project->id,
		);
		$conditions = array_merge($conditions, (array)$this->postConditions($this->data, '=', 'AND', true));
		$this->pageTitle = 'Tickets/Status/';

		$current = $status = $type = $user = null;

		$isDefault = empty($this->passedArgs);

		if ($isDefault) {
			$statuses = $this->Ticket->states();
			$this->passedArgs['status'] = $statuses[0];
		}

		if (!empty($this->passedArgs['status'])) {
			$status = $this->passedArgs['status'];
			$current = $this->passedArgs['status'];
			$conditions['Ticket.status'] = $current;

			if ($status == 'approved') {
				$this->paginate['order'] = 'Ticket.priority ASC';
			}
		}

		if (!empty($this->passedArgs['user'])) {
			$user = $this->passedArgs['user'];
			$current = $this->passedArgs['user'];
			$conditions['Owner.username'] = $this->passedArgs['user'];
			$this->pageTitle = 'Tickets/User/';
		}

		if (!empty($this->passedArgs['type']) && $this->passedArgs['type'] != 'all') {
			$type = $this->passedArgs['type'];
		}
		/*
		if (!empty($this->Project->current['fork'])) {
			$conditions = array('OR' => array(
				array('Ticket.project_id' => $this->Project->id),
				array('Ticket.project_id' => $this->Project->current['project_id'])
			));
		}
		*/
		$this->pageTitle .= Inflector::humanize($current);

		$this->Ticket->recursive = 0;

		$tickets = $this->paginate('Ticket', $conditions);

		$this->Session->write('Ticket.back', '/' . $this->params['url']['url']);
		$this->set(compact('current', 'status', 'type', 'user', 'tickets'));
		$this->_ticketInfo(false);
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

		$this->pageTitle = "Ticket/{$id}/{$ticket['Ticket']['title']}";

		$this->data['Ticket']['tags'] = $this->Ticket->Tag->toString($this->data['Tag']);
		$this->Session->write('Ticket.previous', $this->data['Ticket']);
		$this->set(compact('ticket'));
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

	function _ticketInfo($all = true) {
		if ($all) {
			$versions = $this->Ticket->Version->find('list', array(
				'conditions' => array('Version.project_id' => $this->Project->id
			)));
			$owners = $this->Project->users(array('Permission.group NOT' => 'user'));
		}

		$statuses = $this->Ticket->states();
		$types = $this->Project->ticket('types');
		$priorities = $this->Project->ticket('priorities');
		$resolutions = $this->Project->ticket('resolutions');

		if(!empty($this->data['Ticket']['status'])) {
			$events = $this->Ticket->events($this->data['Ticket']['status']);
		}


		$canUpdate = true;
		if (empty($this->params['isAdmin'])) {
			$canUpdate = $this->Access->check($this, array(
				'access' => 'u', 'default' => false, 'admin' => true
			));
		}

		$this->set(compact(
			'versions', 'types', 'priorities', 'owners', 'events',
			'statuses', 'resolutions',
			'canUpdate'
		));
	}
}

?>