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
		if (empty($this->params['isAdmin'])) {
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			$this->data['Permission']['username'] = $this->Auth->user('username');
			if ($this->Permission->saveFile($this->data)) {
				$this->Session->setFlash('Permissions updated');
			} else {
				$this->Session->setFlash('Permissions NOT updated');
			}
		}
		$this->data['Permission']['fine_grained'] = $this->Permission->file();
		$this->set(compact('users', 'groups'));
	}
}
?>