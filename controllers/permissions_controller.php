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