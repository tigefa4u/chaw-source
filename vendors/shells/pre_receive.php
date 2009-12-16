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
class PreReceiveShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'Permission');
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function _welcome() {}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function main() {
		return $this->access();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function access() {
		$project = @$this->args[0];
		$refname = @$this->args[1];
		$oldrev = @$this->args[2];
		$newrev = @$this->args[3];

		$this->args[] = 'pre-receive';
 		CakeLog::write(LOG_INFO, $this->args);

		$fork = (!empty($this->params['fork']) && $this->params['fork'] != 1) ? $this->params['fork'] : null;

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return false;
		}

		if (empty($_SERVER['PHP_CHAWUSER'])) {
			$this->err('User could not be found');
			return false;
		}

		if ($_SERVER['PHP_CHAWUSER'] == 'chawbacca') {
			return true;
		}

		/*
		$conditions = $this->Project->Repo->find(array('commit' => $newrev), array('email', 'author', 'hash'));
		CakeLog::write(LOG_INFO, $conditions);

		$user = $this->Project->User->field('username', array('OR' => array(
			'email' => $conditions['email'],
			'username' => $conditions['author']
		)));
		*/

		$allowed = $this->Permission->check($refname, array(
			'user' => $_SERVER['PHP_CHAWUSER'],
			'group' => $this->Permission->group($this->Project->id, $_SERVER['PHP_CHAWUSER']),
			'access' => 'w',
			'default' => false
		));
	
		if ($allowed === true) {
			return true;
		}

		$master = $this->Permission->check('refs/heads/master', array(
			'user' => $_SERVER['PHP_CHAWUSER'],
			'group' => $this->Permission->group($this->Project->id, $_SERVER['PHP_CHAWUSER']),
			'access' => 'w',
			'default' => false
		));
		if ($master === true) {
			return true;
		}

		$this->err('Authorization failed');
		return false;
	}

}