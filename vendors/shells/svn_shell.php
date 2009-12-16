<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
class SvnShellShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'Commit');
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $actionMap = array(
		'svnserve' => 'rw',
	);
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function main() {
		if (empty($this->params['user'])) {
			$this->err('User not found.');
			return 1;
		}

		$command = @$this->args[0];

		if (!isset($this->actionMap[$command])) {
			$this->err('Command not found.');
			return 1;
		}

		$this->args[] = 'svn_shell';
		//CakeLog::write(LOG_INFO, $this->args);
		//CakeLog::write(LOG_INFO, $this->params);

		//$this->Project->permit($this->params['user']);

		$path = Configure::read('Content.svn');
		passthru("svnserve -t -r {$path}repo --tunnel-user " . $this->params['user'], $result);
		return $result;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function _welcome() {}
}
