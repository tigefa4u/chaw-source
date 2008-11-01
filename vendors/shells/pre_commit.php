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

	var $uses = array('Project', 'Permission', 'Svn');

	function _welcome() {}

	function main() {
		return $this->authorize();
	}

	function authorize() {
		return true;
		$this->error(print_r($this->args, true));
		die();

		$this->Project->id = $this->args[0];
		$project = $this->Project->field('url');

		$this->Project->initialize(compact('project'));

		$path = Configure::read('Content.svn');

		$this->Svn->config(array(
			'repo' => $path .'repo' . DS . $project,
			'working' => $path .'working' . DS . $project
		));

		$txn = explode('-', $this->args[2]);
		$transaction = $this->args[2];
		$revision = $txn[0];

		$this->log($this->Svn->look('author', array("-t {$transaction}")));
		$this->log($this->Svn->look('changed', array("-t {$transaction}")));
		
		//pr($this->Svn->debug);

		//pr($this->Permission->file());

		return true;
	}

}