<?php
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