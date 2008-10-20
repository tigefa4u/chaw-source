<?php
class PermissionsController extends AppController {

	var $name = 'Permissions';

	function admin_index() {
		$this->admin_add();
		$this->render('admin_add');
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Permission->create(array('project_id' => $this->Project->id));
			if ($this->Permission->save($this->data)) {
				$this->Session->setFlash('Permissions updated');
			} else {
				$this->Session->setFlash('Permissions NOT updated');
			}
		}

		$users = $this->Permission->User->find('list');
		$groups = $this->Project->groups();

		$this->data['Permission']['fine_grained'] = $this->Permission->file();

		$this->set(compact('users', 'groups'));
	}
}
?>