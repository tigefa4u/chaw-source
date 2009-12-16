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
class PostReceiveShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array( 'Project', 'Commit', 'Timeline');
	
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
		return $this->commit();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function commit() {

		$project = str_replace('.git', '', trim(@$this->args[0], "'"));
		$refname = @$this->args[1];
		$oldrev = @$this->args[2];
		$newrev = @$this->args[3];

		//$this->args[] = 'post-receive';
 		//CakeLog::write(LOG_INFO, $this->args);

		$fork = (!empty($this->params['fork']) && $this->params['fork'] != 1) ? $this->params['fork'] : null;

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return false;
		}

		$user = $this->Project->User->field('id', array('username' => $_SERVER['PHP_CHAWUSER']));

		if (!isset($refname)) {
			$refname = 'refs/heads/master';
		}

		//handle branch delete
		if ($newrev == str_pad("0", 40, "0")) {
			$this->Commit->addToTimeline = false;
			$this->Commit->create(array(
				'project_id' =>  $this->Project->id,
				'branch' => $refname,
				'message' => "{$refname} removed",
				'chawuser' => $user
			));

			$this->Commit->save();

			$this->Timeline->create(array(
				'project_id' =>  $this->Project->id,
				'event' => 'removed',
				'data' => $refname,
				'model' => 'Commit',
				'foreign_key' => $this->Commit->id,
				'message' => "{$refname} removed",
				'user_id' => $user
			));

			$this->Timeline->save();
			return;
		}

		$commit = $this->Project->Repo->find('first', array(
			'hash' => $newrev
		));

		//CakeLog::write(LOG_INFO, $commit);

		if (empty($commit)) {
			return;
		}

		//handle new branch
		if ($oldrev == str_pad("0", 40, "0")) {
			$this->Commit->addToTimeline = false;
			$this->Commit->create(array(
				'project_id' =>  $this->Project->id,
				'branch' => $refname,
			));

			$this->Commit->save($commit);

			$this->Timeline->create(array(
				'project_id' =>  $this->Project->id,
				'user_id' => $user,
				'model' => 'Commit',
				'foreign_key' => $this->Commit->id,
				'event' => 'created',
				'data' => $refname,
			));

			$this->Timeline->save();
			return;
		}

		//handle other commits, including push with multiple

		$count = $this->Project->Repo->find('count', array(
			'conditions' => array($oldrev . '..' . $newrev),
			'order' => 'asc'
		));

		$this->Commit->addToTimeline = false;
		$this->Commit->create(array(
			'project_id' =>  $this->Project->id,
			'branch' => $refname,
			'changes' => $oldrev . ".." . $newrev
		));

		$this->Commit->save($commit);

		$this->Timeline->create(array(
			'project_id' =>  $this->Project->id,
			'user_id' => $user,
			'model' => 'Commit',
			'foreign_key' => $this->Commit->id,
			'event' => 'pushed',
			'data' => ($count > 1) ? $count : 0,
		));

		$this->Timeline->save();

		return;
	}

}
