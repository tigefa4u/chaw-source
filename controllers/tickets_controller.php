<?php
class TicketsController extends AppController {

	var $name = 'Tickets';
	
	var $helpers = array('Time');
	
	function index() {
		$this->Ticket->recursive = 0;
		$conditions = null;

		if (!empty($this->params['project'])) {
			$conditions = array('Ticket.project_id' => $this->Project->id);
		}

		$this->set('tickets', $this->paginate('Ticket', $conditions));
	}

	function view($id = null) {
		
		$this->Ticket->contain(array('Tag', 'Comment', 'Comment.User'));
		$ticket = $this->data = $this->Ticket->read(null, $id);
		$this->data['Ticket']['tags'] = $this->Ticket->Tag->toString($this->data['Tag']);
		$this->Session->write('Ticket.previous', $this->data['Ticket']);

		$versions = $this->Ticket->Version->find('list');
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
			}
		}

		$versions = $this->Ticket->Version->find('list');
		$types = $this->Project->ticket('types');
		$priorities = $this->Project->ticket('priorities');

		$this->set(compact('versions', 'types', 'priorities'));
	}

	function modify($id = null) {
		$setPrevious = false;
		if (!empty($this->data)) {
			$this->Ticket->set(array(
				'user_id' => $this->Auth->user('id'),
				'project_id' => $this->Project->id,
				'previous' => $this->Session->read('Ticket.previous')
			));
			
			if ($this->Ticket->save($this->data)) {
				$this->Session->setFlash('Ticket saved');
				$this->Session->delete('Ticket.previous');
			}
		}
		$this->view($id);

		$this->render('view');
	}
}
?>