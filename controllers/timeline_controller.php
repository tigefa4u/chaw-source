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

	var $paginate = array(
		'limit' => 10,
		'order' => 'Timeline.created DESC',
		'contain' => array(
			'Comment', 'Comment.User', 'Comment.Ticket', 'Commit', 'Commit.User', 'Ticket', 'Ticket.Reporter',
			'Wiki', 'Wiki.User'
		),
	);

	function index() {
		$this->Timeline->recursive = 2;

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