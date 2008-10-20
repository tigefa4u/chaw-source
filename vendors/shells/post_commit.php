<?php

class PostCommitShell extends Shell {

	var $uses = array('Project', 'Commit', 'Svn');

	function _welcome() {}

	function main() {
		//$this->_commit();
	}

	function commit() {

		$this->Project->id = $this->args[0];
		$project = $this->Project->field('url');

		$path = Configure::read('Content.svn');

		$this->Svn->config(array(
			'repo' => $path .'repo' . DS . $project,
			'working' => $path .'working' . DS . $project
		));

		$revision = $this->args[2];

		$data = $this->Svn->commit($revision);

		$this->Svn->update();

		if (!empty($data)) {

			$data['Svn']['project_id'] = $this->Project->id;
			$data['Svn']['type'] = 'svn';
			
			$this->Commit->create($data['Svn']);

			return $this->Commit->save();
		}


		return true;
	}

}