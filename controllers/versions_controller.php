<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
 */
class VersionsController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Versions';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $helpers = array('Html', 'Form');

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function index() {
		$this->Version->recursive = 0;
		$this->paginate = array(
			'conditions' => array('Project.id' => $this->Project->id)
		);
		$this->set('versions', $this->paginate());
	}

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Version.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('version', $this->Version->read(null, $id));

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
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

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_add() {
		$this->pageTitle = __("New Version",true);

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

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
	function admin_edit($id = null) {
		if (empty($this->params['isAdmin'])) {
			$this->Session->setFlash(__('Invalid Action.', true));
			$this->redirect(array('admin' => false, 'action'=>'index'));
		}

		$this->pageTitle = __("Modify Version",true);

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