<?php
class TimelineController extends AppController {

	var $name = 'Timeline';

	var $paginate = array('order' => 'Timeline.created DESC');

	function index() {
		$this->Timeline->recursive = 2;
		$this->Timeline->contain(array(
			'Comment', 'Comment.User', 'Comment.Ticket', 'Commit', 'Commit.User', 'Ticket', 'Ticket.Reporter',
			'Wiki', 'Wiki.User'
		));

		$this->paginate = array(
			'order' => 'Timeline.created DESC',
			'contain' => array(
				'Comment', 'Comment.User', 'Comment.Ticket', 'Commit', 'Commit.User', 'Ticket', 'Ticket.Reporter',
				'Wiki', 'Wiki.User'
			),
		);
		if (!empty($this->params['project'])) {
			$this->paginate['conditions'] = array('Timeline.project_id' => $this->Project->id);
		}

		$timeline = $this->paginate();
		$this->set('timeline', $timeline);
	}

	function sync() {

	}
}
?>