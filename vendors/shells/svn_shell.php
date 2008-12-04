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

	var $uses = array('Project', 'Commit');

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
		//$this->log($this->params);
		//$this->log($this->args);

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
	function sync() {
		$project = $this->args[0];
		$fork = @$this->args[1];

		//pr($this->Project->find('all'));

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->config['url'] !== $project) {
			$this->err('Invalid Project');
			return false;
		}

		if ($this->Project->Repo->type !== 'svn') {
			$this->err('Invalid Repo. Check that you supplied the correct project');
			return false;
		}

		$this->out('This may take a while...');
		$this->out('First we start by getting all the previous revisions.');

		$data = array_reverse($this->Project->Repo->find());

		if (!empty($data)) {

			$this->out('Now we can sync up the timeline.');

			$this->Commit->deleteAll(array('Commit.project_id' => $this->Project->id));

			$results = false;
			foreach ($data as $revision) {

				$revision['Repo']['project_id'] = $this->Project->id;

				$this->Commit->create($revision['Repo']);
				if ($this->Commit->save()) {
					$this->out("Commit: {$revision['Repo']['revision']} synced.");
					$results = true;
				}
				sleep(1);
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