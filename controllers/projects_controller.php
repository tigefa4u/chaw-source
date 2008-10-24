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
				$this->Session->setFlash('Project was created');
				pr($this->Project->messages);
				//$this->redirect(array('action' => 'view', $data['Project']['url']));
			} else {
				$this->Session->setFlash('Project was NOT created');
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
				$this->Session->setFlash('Project was updated');
			} else {
				$this->Session->setFlash('Project was NOT updated');
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