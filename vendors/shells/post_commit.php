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
class PostCommitShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'Commit');
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function _welcome() {}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function main() {
		return $this->commit();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function commit() {

		$project = $this->args[0];

		if ($this->Project->initialize(compact('project')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return false;
		}

		$revision = $this->args[2];

		// $this->args[] = 'post_commit';
		// CakeLog::write(LOG_DEBUG, $this->args);
		// CakeLog::write(LOG_DEBUG, $this->params);

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
