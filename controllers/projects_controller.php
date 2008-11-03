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

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	function index() {
		$this->Project->recursive = 0;

		if ($this->params['isAdmin'] === false) {
			$this->paginate = array(
				'conditions' => array('Project.private' => 0)
			);
		}

		$this->set('projects', $this->paginate());
	}

	function view($url  = null) {
		$project = $this->Project->config;
		if (empty($this->params['project']) && $url == null && $project['id'] != 1) {
			$project = $this->Project->findByUrl($url);
		}

		$this->set('project', array('Project' => $project));
	}

	function admin_index() {
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
		$this->render('index');
	}

	function admin_add() {

		$this->pageTitle = 'Project Setup';


		if (!empty($this->data)) {
			$this->Project->create(array(
				'user_id' => $this->Auth->user('id'),
				'approved' => $this->params['isAdmin']
			));
			if ($data = $this->Project->save($this->data)) {
				if (empty($data['Project']['approved'])) {
					$this->Session->setFlash('Project is awaiting approval');
				} else {
					$this->Session->setFlash('Project was created');
				}
				//$this->redirect(array('action' => 'view', $data['Project']['url']));
			} else {
				$this->Session->setFlash('Project was NOT created');
			}
		}

		$this->data = array_merge((array)$this->data, array('Project' => $this->Project->config));
		if (!empty($this->data['Project']['id'])) {
			unset($this->data['Project']['id'], $this->data['Project']['name'], $this->data['Project']['description']);
		}

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);

		$this->render('add');
	}


	function admin_edit($id = null) {

		$this->pageTitle = 'Update Project';

		if (!empty($this->data)) {
			$this->Project->create(array(
				'approved' => $this->params['isAdmin']
			));
			if ($data = $this->Project->save($this->data)) {
				$this->Session->setFlash('Project was updated');
			} else {
				$this->Session->setFlash('Project was NOT updated');
			}
		}

		$this->data = $this->Project->read();

		$this->set('repoTypes', $this->Project->repoTypes());

		$this->set('messages', $this->Project->messages);

		$this->render('edit');
	}
}
?>