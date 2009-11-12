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
class PreCommitShell extends Shell {

	var $uses = array('Project', 'Permission');

	function _welcome() {}

	function main() {
		return $this->authorize();
	}

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

		CakeLog::write(LOG_DEBUG, $this->Project->Repo->look('author', array("-t {$transaction}")));
		CakeLog::write(LOG_DEBUG, $this->Project->Repo->look('changed', array("-t {$transaction}")));

		//pr($this->Project->Repo->debug);

		//pr($this->Permission->file());

		return true;
	}

}