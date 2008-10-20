<?php
class ProjectsController extends AppController {

	var $name = 'Projects';

	function index() {
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
	}

	function view($url  = null) {
		if (!empty($this->params['project'])) {
			$url = $this->params['project'];
		}
		$this->set('project', $this->Project->findByUrl($url));
	}

	function admin_index() {
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
		$this->render('index');
	}

	function admin_add() {

		$this->pageTitle = 'Project Setup';

		if (!empty($this->data)) {
			$this->Project->create();
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash('Setup was stored');
				return;
			}
		}

		$this->data = array_merge((array)$this->data, array('Project' => $this->Project->config));
		if (!empty($this->data['Project']['id'])) {
			unset($this->data['Project']['id']);
		}

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->render('add');
	}


	function admin_edit($id = null) {

		$this->pageTitle = 'Update Project';

		if (!empty($this->data)) {
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash('Setup was stored');
			}
		}

		if (!$id) {
			$id = $this->Project->id;
		}

		$this->data = $this->Project->read(null, $id);

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->render('edit');
	}
}
?>