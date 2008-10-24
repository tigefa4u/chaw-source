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
class CommitsController extends AppController {

	var $name = 'Commits';

	var $paginate = array('order' => 'Commit.created DESC');

	function index() {
		$this->Commit->recursive = 0;
		$conditions = null;

		if (!empty($this->params['project'])) {
			$conditions = array('Commit.project_id' => $this->Project->id);
		}

		$this->set('commits', $this->paginate('Commit', $conditions));
	}

	function view($revision = null) {
		$commit = $this->Commit->findByRevision($revision);
		$this->set('commit', $commit);
	}
}
?>