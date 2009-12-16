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
class GitShellShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'Permission', 'Commit');
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $actionMap = array(
		'git-upload-pack' => 'r',
		'git-receive-pack' => 'rw',
	);
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function main() {
		if (empty($this->params['user'])) {
			$this->err('User not found.');
			return false;
		}

		$this->args[] = 'git_shell';
 		//CakeLog::write(LOG_INFO, $this->args);

		$command = @$this->args[0];

		if (!isset($this->actionMap[$command])) {
			$this->err('Command not found.');
			return false;
		}

		$project = @$this->args[1];

		$fork = null;
		if (strpos($project, 'forks') !== false) {
			$parts = explode('/', $project);
			$fork = $parts[1];
			$project = $parts[2];

		}
		$project = str_replace('.git', '', trim($project, "'"));

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return false;
		}

		if ($this->actionMap[$command] == 'r') {

			$allowed = $this->Permission->check('refs/heads/master', array(
				'user' => $this->params['user'],
				'group' => $this->Permission->group($this->Project->id, $this->params['user']),
				'access' => 'r',
				'default' => (empty($this->Project->current['private'])) ? true : false
			));

			if ($allowed !== true) {
				$this->err('Authorization failed');
				return false;
			}

			$this->Project->permit($this->params['user']);
		}
		$this->Project->Repo->chawuser = $this->params['user'];
		$result = $this->Project->Repo->execute($command, array($this->Project->Repo->path), 'pass');
		return $result;

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function _welcome() {}
}