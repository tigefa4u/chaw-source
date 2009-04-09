<?php
App::import('Model', 'Schema');
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
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class UpgradeOneTask extends ChawUpgradeShell {

	function execute() {
		$this->projects();
		$this->comments();
		$this->resolutions();
	}

	function projects() {
		$this->Project = ClassRegistry::init('Project');

		if ($this->Project->schema('config')) {
			$this->out('projects up to date');
			return true;
		}

		$this->Project->recursive = -1;
		$projects = $this->Project->find('all', array('order' => 'id ASC'));

		if ($this->_updateSchema($this->Project, 'projects') == false) {
			return false;
		}

		extract(array(
			'types' => 'rfc, bug, enhancement',
			'statuses' => 'pending, approved, in progress, on hold, closed',
			'priorities' => 'low, normal, high',
			'resolutions' => 'fixed, invalid, works-for-me, duplicate, wont-fix'
		));

		foreach ($projects as $project) {

			$new = array('config' => array(
				'groups' => $project['Project']['groups'],
				'ticket' => compact('types', 'statuses', 'priorities', 'resolutions')
			));

			$this->Project->setSource('old_projects');
			$this->Project->create($project);
			if (!$this->Project->save($project)) {
				$this->out("ERROR: OLD {$project['Project']['id']}:{$project['Project']['url']} NOT saved");
				continue;
			}
			sleep(1);
			$this->Project->setSource('projects');
			$this->Project->create($project);
			if ($this->Project->save($new)) {
				$this->out("{$project['Project']['id']}:{$project['Project']['url']} upgraded");
			} else {
				$this->out("ERROR: {$project['Project']['id']}:{$project['Project']['url']} NOT upgraded");
			}
		}
	}

	function comments() {
		$this->Comment = ClassRegistry::init('Comment');

		if ($this->Comment->schema('model')) {
			$this->out('comments up to date');
			return true;
		}

		$this->Comment->recursive = -1;
		$comments = $this->Comment->find('all');

		if ($this->_updateSchema($this->Comment, 'comments') == false) {
			return false;
		}

		foreach ($comments as $comment) {
			$this->Comment->setSource('old_comments');
			$this->Comment->create($comment);
			if (!$this->Comment->save($comment)) {
				$this->out("ERROR: OLD {$project['Project']['id']}:{$project['Project']['url']} NOT saved");
				continue;
			}
			sleep(1);
			$new = array(
				'model' => 'Ticket',
				'foreign_key' => $comment['Comment']['ticket_id']
			);
			$this->Comment->setSource('comments');
			$this->Comment->create($comment);
			if ($this->Comment->save($new)) {
				$this->out("Comment {$comment['Comment']['id']} upgraded");
			} else {
				$this->out("ERROR: Comment {$comment['Comment']['id']} NOT upgraded");
			}

		}
	}

	function resolutions() {
		$this->_updateSchema($this->Project, 'resolutions', true);
	}
}
