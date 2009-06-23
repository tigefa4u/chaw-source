<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class PostReceiveShell extends Shell {

	var $uses = array( 'Project', 'Commit');

	function _welcome() {}

	function main() {
		return $this->commit();
	}

	function commit() {

		$project = str_replace('.git', '', trim(@$this->args[0], "'"));
		$refname = @$this->args[1];
		$oldrev = @$this->args[2];
		$newrev = @$this->args[3];

		//$this->args[] = 'post-receive';
 		//$this->log($this->args, LOG_INFO);

		$fork = (!empty($this->params['fork']) && $this->params['fork'] != 1) ? $this->params['fork'] : null;

		if ($this->Project->initialize(compact('project', 'fork')) === false || $this->Project->current['url'] !== $project) {
			$this->err('Invalid project');
			return 1;
		}

		$user = $this->Project->User->field('id', array('username' => $_SERVER['PHP_CHAWUSER']));

		if (!isset($refname)) {
			$refname = 'refs/heads/master';
		}

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

			$this->Commit->save();
			return;
		}

		$conditions = array($oldrev . '..' . $newrev);
		if ($oldrev == str_pad("0", 40, "0")) {
			$conditions = array($newrev);
		}

		$commits = $this->Project->Repo->find('all', array(
			'conditions' => $conditions,
			'order' => 'asc'
		));

		if (!empty($commits)) {

			$push = null;
			foreach ($commits as $i => $data) {
				$this->Commit->addToTimeline = false;
				$this->Commit->create(array(
					'project_id' =>  $this->Project->id,
					'branch' => $refname,
					'chawuser' => $user,
					'pushed_by' => $push
				));

				if ($i == 0) {
					$push = $this->Commit->id;
				}

				$this->Commit->save($data['Repo']);
			}
		}

		return;
	}

}