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
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			commercial
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

		if ($this->Project->initialize(compact('project')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return false;
		}

		$revision = $this->args[2];

		// $this->args[] = 'post_commit';
		// CakeLog::write($this->args, LOG_DEBUG);
		// CakeLog::write($this->params, LOG_DEBUG);

		$data = $this->Project->Repo->read($revision, false);

		$this->Project->Repo->update();

		if (!empty($data)) {
			$this->Project->permit($data['author']);

			$data['project_id'] = $this->Project->id;

			$this->Commit->create($data);

			return $this->Commit->save();
		}

		return true;
	}

}