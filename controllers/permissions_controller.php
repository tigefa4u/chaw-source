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
class PermissionsController extends AppController {

	var $name = 'Permissions';

	function admin_index() {
		if (empty($this->params['isAdmin'])) {
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			$this->data['Permission']['username'] = $this->Auth->user('username');
			if ($this->Permission->saveFile($this->data)) {
				$this->Session->setFlash(__('Permissions updated',true));
			} else {
				$this->Session->setFlash(__('Permissions NOT updated',true));
			}
		}
		$this->data['Permission']['fine_grained'] = $this->Permission->file();

		$groups = $this->Project->groups();

		$this->set(compact('users', 'groups'));
	}


	function admin_remove($id = null) {
		if (!$id || empty($this->params['isAdmin'])) {
			$this->Session->setFlash(__('Invalid request',true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if ($this->Permission->del($id)) {
			$this->Session->setFlash(__('User removed',true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
	}
}
?>