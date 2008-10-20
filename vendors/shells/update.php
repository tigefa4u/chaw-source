<?php

class UpdateShell extends Shell {

	var $uses = array('Project', 'Permission', 'Git');

	function _welcome() {}

	function main() {
		//$this->_commit();
	}
/**
 * undocumented function
 *
 * @return exit value 0 for true, 1-255 for status
 *
 **/
	function authorize() {
		$this->Project->id = $this->args[0];
		$project = $this->Project->field('url');

		$this->Project->initialize(compact('project'));

		$path = Configure::read('Content.git');

		$this->Git->config(array(
			'repo' => $path .'repo' . DS . $project . '.git',
			'working' => $path .'working' . DS . $project
		));


		$refname = $this->args[1];
		$oldrev = $this->args[2];
		$newrev = $this->args[3];

		$info = $this->Git->sub('show', array($newrev, "--pretty=format:'%an'"));

		$username = $info[0];

		$user_id = $this->Permission->User->field('id', array('User.username' => $username));


		$this->Permission->recursive = -1;
		$permissions = $this->Permission->find('all', array(
			'conditions' => array('Permission.user_id' => $user_id, 'Permission.project_id' => $this->Project->id)
		));

		$path = $project .':/' . $refname;

		$allowed = $this->Permission->check($project .':', array(
			'user' => $username,
			'group' => @$permissions['Permission']['group'],
		));

		if ($allowed === true) {
			return 0;
		}

		$allowed = $this->Permission->check($path, array(
			'user' => $username,
			'group' => @$permissions['Permission']['group'],
			'access' => 'w'
		));

		if ($allowed === true) {
			return 0;
		}
		$this->err('Authorization failed');
		return 1;
	}

}