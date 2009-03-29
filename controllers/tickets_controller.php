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

	function _toNamedArgs($keys) {
		foreach ($keys as $key) {
			if (!empty($this->params['url'][$key])) {
				$thie->passedArgs[$key] = $this->params['named'][$key] = join(',', $this->params['url'][$key]);
			}

		}
		return $this->params['named'];
	}

	function index() {
		Router::connectNamed(array('status', 'page', 'user', 'type', 'priority'));
		$searchKeys = array('type', 'priority');

		if (count($this->params['url']) > 2) {
			$this->redirect($this->_toNamedArgs($searchKeys));
		}

		$conditions = array(
			'Ticket.project_id' => $this->Project->id,
		);

		foreach ($searchKeys as $key) {
			if (isset($this->params['named'][$key]) && $this->params['named'][$key] != 'all') {
				$this->data['Ticket'][$key] = explode(',', $this->params['named'][$key]);
			}
		}

		$conditions = array_merge($conditions, (array)$this->postConditions($this->data, '=', 'AND', true));
		$this->pageTitle = 'Tickets/Status/';

		$current = null;

		$isDefault = empty($this->passedArgs);

		if ($isDefault) {
			$statuses = array_values($this->Project->ticket('statuses'));
			$this->passedArgs['status'] = $statuses[0];
		}

		if (!empty($this->passedArgs['status'])) {
			$current = $this->passedArgs['status'];
			$conditions['Ticket.status'] = $current;
		}


		if (!empty($this->passedArgs['user'])) {
			$current = $this->passedArgs['user'];
			$conditions['Owner.username'] = $this->passedArgs['user'];
			$this->pageTitle = 'Tickets/User/';
		}

		if (!empty($this->passedArgs['type']) && $this->passedArgs['type'] == 'all') {
			unset($this->passedArgs['type']);
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

	function _ticketInfo() {
		$versions = $this->Ticket->Version->find('list', array(
			'conditions' => array('Version.project_id' => $this->Project->id
		)));

		$types = $this->Project->ticket('types');
		$priorities = $this->Project->ticket('priorities');
		/*
		$statuses = $this->Project->ticket('statuses');
		$resolutions = $this->Project->ticket('resolutions');
		*/
		$owners = $this->Project->users(array('Permission.group NOT' => 'user'));

		$events = $this->Ticket->events($this->data['Ticket']['status']);

		$canUpdate = $this->Access->check($this, array(
			'access' => 'u', 'default' => false, 'admin' => true
		));

		$this->set(compact(
			'versions', 'types', 'priorities', 'owners', 'events',
			'statuses', 'resolutions',
			'canUpdate'
		));
	}
}

?>