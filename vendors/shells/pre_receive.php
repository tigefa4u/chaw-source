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
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class PreReceiveShell extends Shell {

	var $uses = array('Project', 'Permission');

	function _welcome() {}

	function main() {
		return $this->access();
	}

	function access() {
		$this->args[] = 'pre-receive';
 		$this->log($this->args, LOG_INFO);

		$project = @$this->args[0];
		$refname = @$this->args[1];
		$oldrev = @$this->args[2];
		$newrev = @$this->args[3];

		$fork = (!empty($this->params['fork']) && $this->params['fork'] != 1) ? $this->params['fork'] : null;

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->config['url'] !== $project) {
			$this->err('Invalid project');
			return 1;
		}

		$conditions = $this->Project->Repo->find(array('commit' => $newrev), array('email', 'author', 'hash'));

		$user = $this->Project->User->field('username', array('OR' => array(
			'email' => $conditions['email'],
			'username' => $conditions['author']
		)));

		$allowed = $this->Permission->check($refname, array(
			'user' => $user,
			//'group' => @$permissions['Permission']['group'],
			'access' => 'w',
			'default' => false
		));

		if ($allowed === true) {
			return 0;
		}

		$master = $this->Permission->check('refs/heads/master', array(
			'user' => $user,
			//'group' => @$permissions['Permission']['group'],
			'access' => 'w',
			'default' => false
		));
		if ($master === true) {
			return 0;
		}

		$this->err('Authorization failed');
		return 1;
	}

}