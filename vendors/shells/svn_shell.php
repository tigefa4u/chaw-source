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
class SvnShellShell extends Shell {

	var $uses = array('Project', 'Permission');

	var $actionMap = array(
		'svnserve' => 'rw',
	);
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function main() {

		$this->log($this->params);
		$this->log($this->args);

		if (empty($this->params['user'])) {
			$this->err('User not found.');
			return 1;
		}

		$command = @$this->args[0];

		if (!isset($this->actionMap[$command])) {
			$this->err('Command not found.');
			return 1;
		}

		$path = Configure::read('Content.svn');
		passthru("svnserve -t -r {$path}repo --tunnel-user " . $this->params['user'], $result);
		return $result;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function _welcome() {}
}