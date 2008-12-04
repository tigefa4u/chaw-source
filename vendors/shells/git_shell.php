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
class GitShellShell extends Shell {

	var $uses = array('Project', 'Permission', 'Commit');

	var $actionMap = array(
		'git-upload-pack' => 'r',
		'git-receive-pack' => 'rw',
	);
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function main() {
		if (empty($this->params['user'])) {
			$this->err('User not found.');
			return 1;
		}

		$this->log($this->args, LOG_INFO);
		$this->log($this->params, LOG_INFO);

		$command = @$this->args[0];

		if (!isset($this->actionMap[$command])) {
			$this->err('Command not found.');
			return 1;
		}

		$project = @$this->args[1];

		$fork = null;
		if (strpos($project, 'forks') !== false) {
			$parts = explode('/', $project);
			$fork = $parts[1];
			$project = $parts[2];

		}
		$project = str_replace('.git', '', trim($project, "'"));

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->config['url'] !== $project) {
			$this->err('Invalid project');
			return 1;
		}

		$allowed = $this->Permission->check('refs/heads/master', array(
			'user' => $this->params['user'],
			//'group' => @$permissions['Permission']['group'],
			'access' => $this->actionMap[$command],
			'default' => false
		));

		if ($allowed === true) {
			$result = $this->Project->Repo->execute($command, array($this->Project->Repo->path), 'pass');
			$this->Project->permit($this->params['user']);
			return $result;

		}

		$this->err('Authorization failed');
		return 1;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function sync() {
		$project = $this->args[0];
		$fork = @$this->args[1];

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->config['url'] !== $project) {
			$this->err('Invalid Project');
			return false;
		}

		if ($this->Project->Repo->type !== 'git') {
			$this->err('Invalid Repo. Check that you supplied the correct project');
			return false;
		}

		$this->out('This may take a while...');
		$this->out('First we start by getting all the previous revisions.');

		$data = array_reverse($this->Project->Repo->find('all'));

		if (!empty($data)) {

			$this->out('Now we can sync up the timeline.');

			$this->Commit->deleteAll(array('Commit.project_id' => $this->Project->id));

			$results = false;
			foreach ($data as $revision) {
				
				if (!empty($revision['Repo']['revision'])) {
					$revision['Repo']['project_id'] = $this->Project->id;

					$this->Commit->create($revision['Repo']);
					if ($this->Commit->save()) {
						$this->out("Commit: {$revision['Repo']['revision']} synced.");
						$results = true;
					}
					sleep(1);
				}
			}
		}

		if (!empty($results)) {
			$this->out('Sync complete');
			exit();
		}
		$this->err('Nothing was synced');
		return false;
	
	}	
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function _welcome() {}
}