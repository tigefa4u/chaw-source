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
class PostReceiveShell extends Shell {

	var $uses = array( 'Project', 'Commit', 'Git');

	function _welcome() {}

	function main() {
		//$this->_commit();
	}

	function commit() {

		$project = $this->args[0];
		$refname = @$this->args[1];
		$oldrev = @$this->args[2];
		$newrev = @$this->args[3];

		$path = Configure::read('Content.git');

		$this->Git->config(array(
			'repo' => $path .'repo' . DS . $project . '.git',
			'working' => $path .'working' . DS . $project
		));

		if (!isset($refname)) {
			$refname = 'refs/heads/master';
		}

		$data = $this->Git->commit($newrev);

		/*
		pr($this->Git->update());
		pr($this->Git->debug);

		$this->log($this->Git);
		$this->log($this->args);
		$this->log($this->Git->response);
		$this->log($this->Git->debug);
		*/
		if (!empty($data)) {

			$data['Git']['project_id'] = $this->Project->id;
			$data['Git']['type'] = 'git';

			$this->Commit->create($data['Git']);

			if (!$this->Commit->save()) {
				return false;
			}
		}

		return;
	}

}