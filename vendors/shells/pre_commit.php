<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
class PreCommitShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'Permission');
	
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
		return $this->authorize();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function authorize() {
		$this->args[] = 'pre_commit';
		CakeLog::write(LOG_DEBUG, $this->args);
		return 0;


		$this->error(print_r($this->args, true));
		die();

		$project = @$this->args[0];

		if ($this->Project->initialize(compact('project')) === false) {
			$this->err('Invalid project');
			return false;
		}

		$txn = explode('-', $this->args[2]);
		$transaction = $this->args[2];
		$revision = $txn[0];

		//CakeLog::write(LOG_DEBUG, $this->Project->Repo->look('author', array("-t {$transaction}")));
		//CakeLog::write(LOG_DEBUG, $this->Project->Repo->look('changed', array("-t {$transaction}")));

		//pr($this->Project->Repo->debug);

		//pr($this->Permission->file());

		return true;
	}

}
