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
class VersionsController extends AppController {

	var $name = 'Versions';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->Version->recursive = 0;
		$this->paginate = array(
			'conditions' => array('Project.id' => $this->Project->id)
		);
		$this->set('versions', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Version.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('version', $this->Version->read(null, $id));

	}

	function admin_index() {
		$this->Version->recursive = 0;

		if ($this->Project->id == 1 && empty($this->params['isAdmin'])) {
			$this->paginate = array(
				'conditions' => array('Project.id' => $this->Project->id)
			);
		}

		$this->set('versions', $this->paginate());

		$this->render('index');
	}

	function admin_add() {
		$this->set('title_for_layout', __("New Version",true));

		if (!empty($this->data)) {
			$this->Version->create(array('project_id' => $this->Project->id));
			if ($this->Version->save($this->data)) {
				$this->Session->setFlash(__('The Version has been saved', true));
				$this->redirect(array('admin' => false, 'action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Version could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (empty($this->params['isAdmin'])) {
			$this->Session->setFlash(__('Invalid Action.', true));
			$this->redirect(array('admin' => false, 'action'=>'index'));
		}

		$this->set('title_for_layout', __("Modify Version",true));

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Version', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Version->save($this->data)) {
				$this->Session->setFlash(__('The Version has been saved', true));
				$this->redirect(array('admin' => false, 'action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Version could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Version->read(null, $id);
		}

		if ($this->Project->id == 1 && !empty($this->params['isAdmin'])) {
			$this->set('projects', $this->Version->Project->find('list'));
		}
	}
}
?>
