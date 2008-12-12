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
class PreCommitShell extends Shell {

	var $uses = array('Project', 'Permission');

	function _welcome() {}

	function main() {
		return $this->authorize();
	}

	function authorize() {
		$this->args[] = 'pre_commit';
		$this->log($this->args, LOG_DEBUG);
		return 0;
		
		
		$this->error(print_r($this->args, true));
		die();

		$project = @$this->args[0];

		if ($this->Project->initialize(compact('project')) === false) {
			$this->err('Invalid project');
			return 1;
		}

		$txn = explode('-', $this->args[2]);
		$transaction = $this->args[2];
		$revision = $txn[0];

		$this->log($this->Project->Repo->look('author', array("-t {$transaction}")));
		$this->log($this->Project->Repo->look('changed', array("-t {$transaction}")));

		//pr($this->Project->Repo->debug);

		//pr($this->Permission->file());

		return true;
	}

}