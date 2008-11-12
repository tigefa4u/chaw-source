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
 * @subpackage		chaw.vendors.sheels
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class PostCommitShell extends Shell {

	var $uses = array('Project', 'Commit');

	function _welcome() {}

	function main() {
		return $this->commit();
	}

	function commit() {

		$project = $this->args[0];

		if ($this->Project->initialize(compact('project')) === false) {
			$this->err('Invalid project');
			return 1;
		}

		$revision = $this->args[2];

		$data = $this->Project->Repo->read($revision, false);

		$this->Project->Repo->update();

		if (!empty($data)) {

			$data['project_id'] = $this->Project->id;

			$this->Commit->create($data);

			return $this->Commit->save();
		}


		return true;
	}

}