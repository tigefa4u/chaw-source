<?php
class VersionsController extends AppController {

	var $name = 'Versions';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->Version->recursive = 0;
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
		$this->set('versions', $this->paginate());

		$this->render('index');
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Version.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('version', $this->Version->read(null, $id));

		$this->render('view');
	}

	function admin_add() {
		$this->pageTitle = "New Version";

		if (!empty($this->data)) {
			$this->Version->create();
			if ($this->Version->save($this->data)) {
				$this->Session->setFlash(__('The Version has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Version could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		$this->pageTitle = "Update Version";

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Version', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Version->save($this->data)) {
				$this->Session->setFlash(__('The Version has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Version could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Version->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Version', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Version->del($id)) {
			$this->Session->setFlash(__('Version deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>