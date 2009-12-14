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
class PermissionsController extends AppController {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Permissions';

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function admin_index() {
		if (empty($this->params['isAdmin'])) {
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			$this->data['Permission']['username'] = $this->Auth->user('username');
			if (!empty($this->params['form']['default'])) {
				$this->data = array('username' => '@admin');
			}
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

	/**
	 * undocumented function
	 *
	 * @param string $id
	 * @return void
	 */
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